<?php

namespace App\Http\Controllers;

use App\Models\Questionnaire;
use Illuminate\Http\Request;

class QuestionnaireController extends Controller
{
    public function index(Request $request)
    {

      //  $questionnaires = Questionnaire::all();
        $user = auth()->user();
        $childId = $request->query('child_id');

        if ($user->isParent()) {

            if (!$childId) {
                return redirect()->route('parent.dashboard')
                    ->withErrors(['error' => 'Please select a child first.']);
            }
            else {
                $child = $user->children->firstWhere('id', $childId);
                abort_if(!$child, 403);
            }
            $questionnaires = Questionnaire::where('target_role', 'parent')->get();

            $completedIds = \App\Models\Assessment::where('filled_for_user_id', $childId)
                ->pluck('questionnaire_id')
                ->toArray();
        } else {
            $questionnaires = Questionnaire::where('target_role', 'student')->get();

            $completedIds = \App\Models\Assessment::where('filled_for_user_id', $user->id)
                ->pluck('questionnaire_id')
                ->toArray();
        }

        return view('questionnaires.index', compact('questionnaires', 'completedIds', 'childId'));
    }
}
