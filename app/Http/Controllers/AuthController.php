<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CoachProfile;
use App\Models\ClientProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Show registration form
    public function showRegister()
    {
        return view('auth.register');
    }

    // Handle registration
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'role' => 'required|in:coach,client',
        ]);

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'],
            'role' => $validated['role'],
        ]);

        // Create profile based on role
       // Create profile based on role
        if ($validated['role'] === 'coach') {
            CoachProfile::create([
                'user_id' => $user->id,
                'specialization' => 'General Fitness',
                'experience_years' => 0,
                'hourly_rate' => 0,  // ADD THIS LINE
            ]);
        } else {
            ClientProfile::create([
                'user_id' => $user->id,
                'age' => 0,
                'height' => 0,
                'weight' => 0,
                'fitness_goal' => 'General Fitness',
            ]);
        }


        Auth::login($user);

        return redirect()->route($validated['role'] . '.dashboard')
            ->with('success', 'Registration successful!');
    }

    // Show login form
    public function showLogin()
    {
        return view('auth.login');
    }

    // Handle login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            return redirect()->route($user->role . '.dashboard')
                ->with('success', 'Login successful!');
        }

        return back()->withErrors(['email' => 'Invalid credentials'])
            ->onlyInput('email');
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Logged out successfully!');
    }

    // Update profile
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'bio' => 'nullable|string|max:500',
            'phone' => 'sometimes|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar_url'] = $path;
        }

        $user->update($validated);

        return back()->with('success', 'Profile updated successfully!');
    }
}
