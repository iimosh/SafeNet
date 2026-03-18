<?php


namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:student,parent'],
        ]);

        if ($request->role === 'parent') {
            $request->validate([
                'child_email' => [
                    'required',
                    'email',
                    function ($attribute, $value, $fail) {
                        $student = \App\Models\User::where('email', $value)
                            ->where('role', 'student')
                            ->first();
                        if (!$student) {
                            $fail('No student account found with this email.');
                        }
                    }
                ],
            ]);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        if ($request->role === 'parent') {
            $student = User::where('email', $request->child_email)->first();
            $user->children()->attach($student->id);
        }

        event(new Registered($user));
        Auth::login($user);

        return match(auth()->user()->role) {
            'parent' => redirect()->route('parent.dashboard'),
            default  => redirect()->route('dashboard'),
        };
    }
}
