<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    /**
     * Show the contact form
     */
    public function show()
    {
        return view('contact');
    }

    /**
     * Handle contact form submission
     */
    public function submit(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'subject' => 'required|string|max:200',
            'message' => 'required|string|max:2000',
            'phone' => 'nullable|string|max:20',
            'inquiry_type' => 'required|in:general,technical,support,business,other',
        ], [
            'name.required' => 'Please enter your name.',
            'name.max' => 'Name cannot exceed 100 characters.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'email.max' => 'Email cannot exceed 100 characters.',
            'subject.required' => 'Please enter a subject.',
            'subject.max' => 'Subject cannot exceed 200 characters.',
            'message.required' => 'Please enter your message.',
            'message.max' => 'Message cannot exceed 2000 characters.',
            'phone.max' => 'Phone number cannot exceed 20 characters.',
            'inquiry_type.required' => 'Please select an inquiry type.',
            'inquiry_type.in' => 'Please select a valid inquiry type.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        // Basic spam protection - check for suspicious content
        $message = $request->message;
        $suspiciousKeywords = ['viagra', 'casino', 'loan', 'credit', 'debt', 'investment', 'make money fast'];
        $suspiciousCount = 0;
        
        foreach ($suspiciousKeywords as $keyword) {
            if (stripos($message, $keyword) !== false) {
                $suspiciousCount++;
            }
        }

        if ($suspiciousCount > 2) {
            return back()
                ->withErrors(['message' => 'Your message contains suspicious content. Please review and try again.'])
                ->withInput();
        }

        // Rate limiting - check if too many submissions from same IP
        $ip = $request->ip();
        $recentSubmissions = \Cache::get("contact_submissions_{$ip}", 0);
        
        if ($recentSubmissions > 5) {
            return back()
                ->withErrors(['message' => 'Too many contact form submissions. Please try again later.'])
                ->withInput();
        }

        // Increment submission count
        \Cache::put("contact_submissions_{$ip}", $recentSubmissions + 1, 3600); // 1 hour

        try {
            // Prepare email data
            $emailData = [
                'name' => $request->name,
                'email' => $request->email,
                'subject' => $request->subject,
                'message' => $request->message,
                'phone' => $request->phone,
                'inquiry_type' => $request->inquiry_type,
                'ip_address' => $ip,
                'user_agent' => $request->userAgent(),
                'submitted_at' => now()->format('Y-m-d H:i:s'),
            ];

            // For development, we'll store in session and show success
            // In production, you would send actual email here
            session()->flash('contact_success', [
                'name' => $request->name,
                'email' => $request->email,
                'subject' => $request->subject,
                'message' => $request->message,
                'phone' => $request->phone,
                'inquiry_type' => $request->inquiry_type,
            ]);

            // Log the contact form submission
            \Log::info('Contact form submission', $emailData);

            return redirect('/contact')->with('success', 'Thank you for your message! We will get back to you within 24 hours.');

        } catch (\Exception $e) {
            \Log::error('Contact form error: ' . $e->getMessage());
            
            return back()
                ->withErrors(['message' => 'Sorry, there was an error sending your message. Please try again later.'])
                ->withInput();
        }
    }
}
