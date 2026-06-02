<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function generate(Assessment $assessment)
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

        $pairedAssessment = $assessment->findPaired();

        $student = \App\Models\User::find($assessment->filled_for_user_id);

        $pdf = Pdf::loadView('reports.assessment', [
            'assessment'       => $assessment,
            'breakdown'        => $breakdown,
            'pairedAssessment' => $pairedAssessment,
            'student'          => $student,
        ]);

        return $pdf->download('report-' . $student->name . '.pdf');
    }
}
