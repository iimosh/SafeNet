<?php


namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\AssessmentAnswer;
use App\Models\Option;
use App\Models\Questionnaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssessmentController extends Controller
{

    public function index()
    {
        $assessments = Assessment::where('filled_for_user_id', auth()->id())
            ->latest()
            ->get();

        return view('assessment.index', compact('assessments'));
    }

    public function start(Request $request)
    {
        $questionnaireId = $request->query('questionnaire_id');

        // When no specific questionnaire is requested, fall back to the latest one
        // that targets the caller's role — never serve a parent questionnaire to a
        // student (or vice versa).
        $targetRole = auth()->user()->isParent() ? 'parent' : 'student';

        $questionnaire = $questionnaireId
            ? Questionnaire::with(['categories.questions.options'])->findOrFail($questionnaireId)
            : Questionnaire::with(['categories.questions.options'])
                ->where('target_role', $targetRole)
                ->latest()
                ->firstOrFail();


        if (auth()->user()->isParent()) {
            $children = auth()->user()->children;

            if ($children->isEmpty()) {
                return redirect()->route('parent.dashboard')
                    ->withErrors(['error' => 'You have no linked children.']);
            }

            $selectedChildId = $request->query('child_id');

            if (!$selectedChildId) {
                return view('assessment.select-child', compact('children'));
            }

            $child = $children->firstWhere('id', $selectedChildId);
            if (!$child) {
                abort(403);
            }

            $alreadyDone = \App\Models\Assessment::where('filled_for_user_id', $selectedChildId)
                ->where('questionnaire_id', $questionnaire->id)
                ->exists();

            if ($alreadyDone) {
                return redirect()->route('questionnaires.index', ['child_id' => $selectedChildId])
                    ->withErrors(['error' => 'This questionnaire has already been completed for this child.']);
            }

            return view('questionnaire', compact('questionnaire', 'selectedChildId'));
        }
        $selectedChildId = auth()->id();

        $alreadyDone = \App\Models\Assessment::where('filled_for_user_id', $selectedChildId)
            ->where('questionnaire_id', $questionnaire->id)
            ->exists();

        if ($alreadyDone) {
            return redirect()->route('questionnaires.index')
                ->withErrors(['error' => 'You have already completed this questionnaire.']);
        }

        return view('questionnaire', compact('questionnaire', 'selectedChildId'));

    }

    public function submit(Request $request)
    {
        $questionnaireId = $request->input('questionnaire_id');
        $questionnaire = Questionnaire::with(['categories.questions.options'])->findOrFail($questionnaireId);

        $answers = $request->input('answers', []);

        $allQuestions = $questionnaire->categories->flatMap->questions;

        foreach ($allQuestions as $question) {
            if (!isset($answers[$question->id])) {
                return back()
                    ->withErrors(['answers' => 'Please answer all questions.'])
                    ->withInput();
            }
        }

        $filledForUserId = $request->input('filled_for_user_id');

        if (auth()->user()->isParent() && !$filledForUserId) {
            return redirect()->route('parent.dashboard')
                ->withErrors(['error' => 'No child selected.']);
        }

        if (!$filledForUserId) {
            $filledForUserId = auth()->id();
        }

        $assessment = DB::transaction(function () use ($questionnaire, $answers, $filledForUserId) {
            $total = 0;
            $maxPoints = 0;
            $categoryBreakdown = [];

            $assessment = Assessment::create([
                'user_id' => auth()->id(),
                'filled_for_user_id' => $filledForUserId,
                'questionnaire_id' => $questionnaire->id,
                'total_points' => 0,
                'max_points' => 0,
                'risk_level' => 'low',
                'global_recommendation' => null,
                'category_breakdown' => [],
            ]);

            foreach ($questionnaire->categories as $category) {
                $categoryScore = 0;
                $categoryMax = 0;

                foreach ($category->questions as $question) {
                    $optionId = (int) $answers[$question->id];

                    $option = Option::where('id', $optionId)
                        ->where('question_id', $question->id)
                        ->firstOrFail();

                    $points = (int) $option->risk_points;
                    $questionMax = (int) $question->options->max('risk_points');

                    $categoryScore += $points;
                    $categoryMax += $questionMax;
                    $total += $points;
                    $maxPoints += $questionMax;

                    AssessmentAnswer::create([
                        'assessment_id' => $assessment->id,
                        'question_id' => $question->id,
                        'option_id' => $option->id,
                        'points' => $points,
                        'category' => $category->name,
                    ]);
                }

                $categoryRisk = $this->resolveRiskLevel($categoryScore, $categoryMax);
                $categoryRecommendation = $this->getCategoryRecommendation($questionnaire->id, $category->id, $categoryRisk);

                $categoryBreakdown[] = [
                    'category_id' => $category->id,
                    'category_name' => $category->name,
                    'score' => $categoryScore,
                    'max_score' => $categoryMax,
                    'risk_level' => $categoryRisk,
                    'recommendation' => $categoryRecommendation,
                ];
            }

            $globalRisk = $this->resolveRiskLevel($total, $maxPoints);
            $globalRecommendation = $this->getGlobalRecommendation($questionnaire->id, $globalRisk);

            $assessment->update([
                'total_points' => $total,
                'max_points' => $maxPoints,
                'risk_level' => $globalRisk,
                'global_recommendation' => $globalRecommendation,
                'category_breakdown' => $categoryBreakdown,
            ]);

            return $assessment;
        });

        return redirect()->route('assessment.show', $assessment)
            ->with('from_role', auth()->user()->role);
    }

    public function show(Assessment $assessment)
    {
        $user = auth()->user();

        $canView = match($user->role) {
            'admin'  => true,
            'parent' => $user->children->contains('id', $assessment->filled_for_user_id),
            default  => $assessment->filled_for_user_id === $user->id,
        };

        abort_if(!$canView, 403);

        $assessment->load('questionnaire', 'answers.question', 'answers.option');

        $breakdown = collect($assessment->category_breakdown ?? []);

        return view('result', [
            'assessment'       => $assessment,
            'breakdown'        => $breakdown,
            'pairedAssessment' => $assessment->findPaired(),
            'backRoute'        => auth()->user()->isParent()
                ? route('parent.dashboard')
                : route('dashboard'),
        ]);
    }

    private function resolveRiskLevel(int $score, int $maxScore): string
    {
        if ($maxScore === 0) {
            return 'low';
        }

        $percentage = ($score / $maxScore) * 100;

        return match (true) {
            $percentage <= 30 => 'low',
            $percentage <= 60 => 'medium',
            default => 'high',
        };
    }

    private function getCategoryRecommendation(int $questionnaireId, int $categoryId, string $riskLevel): string
    {
        $recommendation = \App\Models\Recommendation::where('questionnaire_id', $questionnaireId)
            ->where('category_id', $categoryId)
            ->where('risk_level', $riskLevel)
            ->first();

        return $recommendation?->text ?? 'Нема дефинирана препорака за оваа категорија.';
    }

    private function getGlobalRecommendation(int $questionnaireId, string $riskLevel): string
    {
        $recommendation = \App\Models\Recommendation::where('questionnaire_id', $questionnaireId)
            ->where('is_global', true)
            ->where('risk_level', $riskLevel)
            ->first();

        return $recommendation?->text ?? 'Нема дефинирана глобална препорака.';
    }
}
