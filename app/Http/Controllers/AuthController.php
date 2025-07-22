<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle login
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            'user_type' => 'required|in:owner,renter',
        ], [
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'password.required' => 'Please enter your password.',
            'user_type.required' => 'Please select your user type.',
            'user_type.in' => 'Please select a valid user type.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $email = $request->email;
        $password = $request->password;
        $userType = $request->user_type;

        // Find user with specific role
        $user = User::where('email', $email)
            ->where('role', $userType)
            ->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return back()
                ->withErrors(['email' => 'Invalid credentials for selected user type.'])
                ->withInput();
        }

        if (!$user->isActive()) {
            return back()
                ->withErrors(['email' => 'Your account is inactive. Please contact support.'])
                ->withInput();
        }

        // Regenerate session ID for security
        session()->regenerate();
        
        session(['user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'role' => $user->role,
            'role_display' => $user->getRoleDisplayName(),
        ]]);
        
        session()->save();

        // Redirect based on user type
        if ($user->isRoomOwner()) {
            return redirect('/dashboard')->with('success', 'Welcome back, ' . $user->name . '!');
        } else {
            return redirect('/')->with('success', 'Welcome back, ' . $user->name . '!');
        }
    }

    /**
     * Show signup form
     */
    public function showSignup()
    {
        return view('auth.signup');
    }

    /**
     * Handle signup
     */
    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:6|confirmed',
            'user_type' => 'required|in:owner,renter',
            'terms' => 'required|accepted',
        ], [
            'name.required' => 'Please enter your full name.',
            'name.max' => 'Name cannot exceed 100 characters.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already registered.',
            'phone.required' => 'Please enter your phone number.',
            'phone.max' => 'Phone number cannot exceed 20 characters.',
            'password.required' => 'Please enter a password.',
            'password.min' => 'Password must be at least 6 characters long.',
            'password.confirmed' => 'Password confirmation does not match.',
            'user_type.required' => 'Please select your user type.',
            'user_type.in' => 'Please select a valid user type.',
            'terms.required' => 'You must accept the terms and conditions.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role' => $request->user_type,
                'is_active' => true,
            ]);

            // Regenerate session ID for security
            session()->regenerate();
            
            session(['user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => $user->role,
                'role_display' => $user->getRoleDisplayName(),
            ]]);
            
            session()->save();

            // Redirect based on user type
            if ($user->isRoomOwner()) {
                return redirect('/dashboard')->with('success', 'Account created successfully! Welcome to StayRoomz.');
            } else {
                return redirect('/')->with('success', 'Account created successfully! Start browsing rooms.');
            }

        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'An error occurred while creating your account. Please try again.'])
                ->withInput();
        }
    }

    /**
     * Handle logout
     */
    public function logout()
    {
        session()->forget('user');
        session()->regenerate();
        
        return redirect('/')->with('success', 'You have been logged out successfully.');
    }

    /**
     * Show user profile
     */
    public function showProfile()
    {
        if (!session('user')) {
            return redirect('/login');
        }

        $user = User::find(session('user')['id']);
        return view('auth.profile', compact('user'));
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        if (!session('user')) {
            return redirect('/login');
        }

        $user = User::find(session('user')['id']);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'current_password' => 'nullable|string',
            'new_password' => 'nullable|string|min:6|confirmed',
        ], [
            'name.required' => 'Please enter your full name.',
            'name.max' => 'Name cannot exceed 100 characters.',
            'phone.required' => 'Please enter your phone number.',
            'phone.max' => 'Phone number cannot exceed 20 characters.',
            'new_password.min' => 'New password must be at least 6 characters long.',
            'new_password.confirmed' => 'Password confirmation does not match.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check current password if provided
        if ($request->current_password) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()
                    ->withErrors(['current_password' => 'Current password is incorrect.'])
                    ->withInput();
            }
        }

        try {
            $user->update([
                'name' => $request->name,
                'phone' => $request->phone,
            ]);

            // Update password if provided
            if ($request->new_password) {
                $user->update([
                    'password' => Hash::make($request->new_password)
                ]);
            }

            // Update session
            session(['user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => $user->role,
                'role_display' => $user->getRoleDisplayName(),
            ]]);

            return back()->with('success', 'Profile updated successfully!');

        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'An error occurred while updating your profile. Please try again.'])
                ->withInput();
        }
    }
}
