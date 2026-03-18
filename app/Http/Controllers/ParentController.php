<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ParentController extends Controller
{
    public function addChild(Request $request)
    {
        $request->validate([
            'child_email' => [
                'required',
                'email',
                function ($attribute, $value, $fail) {
                    $student = User::where('email', $value)
                        ->where('role', 'student')
                        ->first();
                    if (!$student) {
                        $fail('No student account found with this email.');
                    }
                }
            ],
        ]);

        $student = User::where('email', $request->child_email)->first();

        if (auth()->user()->children->contains($student->id)) {
            return back()->withErrors(['child_email' => 'This child is already linked to your account.']);
        }

        auth()->user()->children()->attach($student->id);

        return back()->with('success', 'Child added successfully.');
    }
}
