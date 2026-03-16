<?php


namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\AssessmentAnswer;
use App\Models\Option;
use App\Models\Questionnaire;
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

        $assessment = Assessment::create([
            'user_id' => auth()->id(),
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

        return redirect()->route('assessment.show', $assessment);
    }

    public function show(Assessment $assessment)
    {
        abort_if(
            $assessment->user_id !== auth()->id() && auth()->user()->role !== 'admin',
            403
        );

        $assessment->load('questionnaire', 'answers.question', 'answers.option');

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
