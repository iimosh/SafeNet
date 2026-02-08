<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\AssessmentAnswer;
use App\Models\Questionnaire;
use App\Models\Option;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    public function start()
    {
        $questionnaire = Questionnaire::with('questions.options')->latest()->firstOrFail();
        return view('questionnaire', compact('questionnaire'));
    }

    public function submit(Request $request)
    {
        $questionnaireId = $request->input('questionnaire_id');

        $questionnaire = Questionnaire::with('questions.options')->findOrFail($questionnaireId);

        // answers[question_id] = option_id
        $answers = $request->input('answers', []);

        // Basic validation: require answer for each question
        foreach ($questionnaire->questions as $q) {
            if (!isset($answers[$q->id])) {
                return back()->withErrors(['answers' => 'Please answer all questions.'])->withInput();
            }
        }

        $total = 0;
        $breakdown = []; // category => points

        // Create assessment
        $assessment = Assessment::create([
            'user_id' => auth()->id(),
            'questionnaire_id' => $questionnaire->id,
            'total_points' => 0,
            'risk_level' => 'low',
            'category_breakdown' => null,
        ]);

        foreach ($questionnaire->questions as $q) {
            $optionId = (int) $answers[$q->id];
            $option = Option::where('id', $optionId)->where('question_id', $q->id)->firstOrFail();

            $points = (int) $option->risk_points;
            $total += $points;

            $cat = $q->category ?? 'general';
            $breakdown[$cat] = ($breakdown[$cat] ?? 0) + $points;

            AssessmentAnswer::create([
                'assessment_id' => $assessment->id,
                'question_id' => $q->id,
                'option_id' => $option->id,
                'points' => $points,
                'category' => $cat,
            ]);
        }

        $risk = $this->riskLevel($total);

        $assessment->update([
            'total_points' => $total,
            'risk_level' => $risk,
            'category_breakdown' => $breakdown,
        ]);

        return redirect()->route('assessment.show', $assessment);
    }

    public function show(Assessment $assessment)
    {
        // security: only owner (or admin later)
        abort_if($assessment->user_id !== auth()->id() && auth()->user()->role !== 'admin', 403);

        $assessment->load('questionnaire', 'answers.question', 'answers.option');

        // sort breakdown by highest points
        $breakdown = collect($assessment->category_breakdown ?? [])->sortDesc();

        return view('result', [
            'assessment' => $assessment,
            'breakdown' => $breakdown,
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
