<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;

class PasswordResetController extends Controller
{
    /**
     * Show the password reset request form
     */
    public function showResetRequestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send password reset email
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'email.exists' => 'We could not find a user with that email address.',
        ]);

        $email = $request->email;
        $token = Str::random(64);
        
        // Store the reset token
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'email' => $email,
                'token' => $token,
                'created_at' => now()
            ]
        );

        // For development: Show the reset link on screen
        // In production, you would integrate with a mail service like Mailgun, SendGrid, etc.
        $resetUrl = url("/reset-password/{$token}?email=" . urlencode($email));
        
        return redirect('/forgot-password')->with('success', 'Password reset link has been generated. For development purposes, you can use this link: ' . $resetUrl);
    }

    /**
     * Show the password reset form
     */
    public function showResetForm(Request $request, $token)
    {
        $email = $request->query('email');
        
        // Verify token exists and is not expired (24 hours)
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->where('token', $token)
            ->where('created_at', '>', now()->subHours(24))
            ->first();

        if (!$resetRecord) {
            return redirect('/forgot-password')->with('error', 'Invalid or expired password reset link.');
        }

        return view('auth.reset-password', compact('email', 'token'));
    }

    /**
     * Reset the password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|min:6|confirmed',
        ], [
            'password.required' => 'Please enter a new password.',
            'password.min' => 'Password must be at least 6 characters long.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        $email = $request->email;
        $token = $request->token;
        $password = $request->password;

        // Verify token
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->where('token', $token)
            ->where('created_at', '>', now()->subHours(24))
            ->first();

        if (!$resetRecord) {
            return back()->withErrors(['email' => 'Invalid or expired password reset link.']);
        }

        // Update user password
        $user = User::where('email', $email)->first();
        if ($user) {
            $user->update([
                'password' => Hash::make($password)
            ]);

            // Delete the reset token
            DB::table('password_reset_tokens')
                ->where('email', $email)
                ->delete();

            return redirect('/login')->with('success', 'Your password has been reset successfully. You can now login with your new password.');
        }

        return back()->withErrors(['email' => 'User not found.']);
    }
}
