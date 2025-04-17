<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function login_page(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|Factory
    {
        return view('auth.login');
    }

    public function register_page(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|Factory
    {
        return view('auth.register');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:5',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']], $remember)) {
            // Regenerează token-ul de sesiune
            $request->session()->regenerate();

            // Redirectează la pagina de start
            return redirect()->route('home');
        }

        return back()
            ->withErrors(['email' => 'Email sau parolă incorecte.'])
            ->withInput();
    }

    public function register(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:5|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'role' => 'user',
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        if ($user) {
            return redirect()->route('login')->with('success', 'Registration was successful. Please log in.');
        }

        return back()->withErrors(['register' => 'An error occurred while registering. Please try again.']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
