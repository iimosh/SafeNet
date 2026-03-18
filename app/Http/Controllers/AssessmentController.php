<?php


namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\AssessmentAnswer;
use App\Models\Option;
use App\Models\Questionnaire;
use Illuminate\Http\Request;

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

        $questionnaire = $questionnaireId
            ? Questionnaire::with('questions.options')->findOrFail($questionnaireId)
            : Questionnaire::with('questions.options')->latest()->firstOrFail();
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
        $questionnaire = Questionnaire::with('questions.options')->findOrFail($questionnaireId);

        $answers = $request->input('answers', []);

        foreach ($questionnaire->questions as $question) {
            if (!isset($answers[$question->id])) {
                return back()
                    ->withErrors(['answers' => 'Please answer all questions.'])
                    ->withInput();
            }
        }

        $total = 0;
        $breakdown = [];

        $filledForUserId = $request->input('filled_for_user_id');

        if (auth()->user()->isParent() && !$filledForUserId) {
            return redirect()->route('parent.dashboard')
                ->withErrors(['error' => 'No child selected.']);
        }

        if (!$filledForUserId) {
            $filledForUserId = auth()->id();
        }

        $assessment = Assessment::create([
            'user_id' => auth()->id(),
            'filled_for_user_id' => $filledForUserId,
            'questionnaire_id' => $questionnaire->id,
            'total_points' => 0,
            'risk_level' => 'low',
            'category_breakdown' => null,
        ]);

        foreach ($questionnaire->questions as $question) {
            $optionId = (int)$answers[$question->id];

            $option = Option::where('id', $optionId)
                ->where('question_id', $question->id)
                ->firstOrFail();

            $points = (int)$option->risk_points;
            $total += $points;

            $category = $question->category ?? 'general';
            $breakdown[$category] = ($breakdown[$category] ?? 0) + $points;

            AssessmentAnswer::create([
                'assessment_id' => $assessment->id,
                'question_id' => $question->id,
                'option_id' => $option->id,
                'points' => $points,
                'category' => $category,
            ]);
        }

        $assessment->update([
            'total_points' => $total,
            'risk_level' => $this->riskLevel($total),
            'category_breakdown' => $breakdown,
        ]);

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

        $breakdown = collect($assessment->category_breakdown ?? [])->sortDesc();

        return view('result', [
            'assessment' => $assessment,
            'breakdown'  => $breakdown,
            'backRoute'  => auth()->user()->isParent()
                ? route('parent.dashboard')
                : route('dashboard'),
        ]);
    }

    private function riskLevel(int $points): string
    {
        return match (true) {
            $points <= 20 => 'low',
            $points <= 50 => 'medium',
            default => 'high',
        };
    }
}
