<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\Payment;
use App\Models\Room;
use App\Models\User;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PaymentController extends Controller
{
    /**
     * Show payment form for a specific room
     */
    public function showPaymentForm($roomId)
    {
        if (!session('user')) {
            return redirect('/login');
        }

        $user = User::find(session('user')['id']);
        $room = Room::with('user')->findOrFail($roomId);

        // Check if user is a renter
        if (!$user->isRenter()) {
            return redirect('/')->with('error', 'Only renters can make payments.');
        }

        // Check if room is available
        if ($room->availability !== 'available') {
            return redirect('/')->with('error', 'This room is not available for rent.');
        }

        return view('payments.create', compact('room', 'user'));
    }

    /**
     * Create a new payment
     */
    public function createPayment(Request $request, $roomId)
    {
        if (!session('user')) {
            return redirect('/login');
        }

        $user = User::find(session('user')['id']);
        $room = Room::with('user')->findOrFail($roomId);

        // Validate request
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
            'payment_type' => 'required|in:rent,deposit,maintenance,other',
            'payment_method' => 'required|in:upi,bank_transfer,cash,cheque,online',
            'description' => 'nullable|string|max:500',
            'due_date' => 'nullable|date|after:today',
        ], [
            'amount.required' => 'Please enter the payment amount.',
            'amount.numeric' => 'Amount must be a valid number.',
            'amount.min' => 'Amount must be at least ₹1.',
            'payment_type.required' => 'Please select payment type.',
            'payment_type.in' => 'Please select a valid payment type.',
            'payment_method.required' => 'Please select payment method.',
            'payment_method.in' => 'Please select a valid payment method.',
            'description.max' => 'Description cannot exceed 500 characters.',
            'due_date.after' => 'Due date must be in the future.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check if user is a renter
        if (!$user->isRenter()) {
            return back()->with('error', 'Only renters can make payments.');
        }

        // Check if room is available
        if ($room->availability !== 'available') {
            return back()->with('error', 'This room is not available for rent.');
        }

        try {
            // Create payment record
            $payment = Payment::create([
                'renter_id' => $user->id,
                'landlord_id' => $room->user_id,
                'room_id' => $room->id,
                'payment_id' => Payment::generatePaymentId(),
                'amount' => $request->amount,
                'payment_type' => $request->payment_type,
                'payment_method' => $request->payment_method,
                'description' => $request->description,
                'due_date' => $request->due_date,
                'status' => 'pending',
            ]);

            // Generate QR code for payment
            $qrCodeData = [
                'payment_id' => $payment->payment_id,
                'amount' => $payment->amount,
                'landlord_phone' => $room->phone,
                'landlord_name' => $room->owner_name,
                'room_title' => $room->title,
            ];

            // Generate QR code as SVG (doesn't require imagick)
            try {
                $qrCode = QrCode::format('svg')
                    ->size(300)
                    ->generate(json_encode($qrCodeData));

                // Save QR code
                $qrCodePath = 'payments/qr_codes/' . $payment->payment_id . '.svg';
                Storage::disk('public')->put($qrCodePath, $qrCode);
                $payment->update(['qr_code' => $qrCodePath]);
            } catch (\Exception $e) {
                // If QR code generation fails, continue without QR code
                \Log::warning('QR code generation failed: ' . $e->getMessage());
                $payment->update(['qr_code' => null]);
            }

            return redirect()->route('payment.show', $payment->payment_id)
                ->with('success', 'Payment created successfully! Please complete the payment using the QR code.');

        } catch (\Exception $e) {
            \Log::error('Payment creation error: ' . $e->getMessage());
            \Log::error('Payment creation error trace: ' . $e->getTraceAsString());
            return back()
                ->with('error', 'Failed to create payment: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show payment details and QR code
     */
    public function showPayment($paymentId)
    {
        if (!session('user')) {
            return redirect('/login');
        }

        $user = User::find(session('user')['id']);
        $payment = Payment::with(['room', 'landlord', 'renter'])->where('payment_id', $paymentId)->firstOrFail();

        // Check if user is authorized to view this payment
        if ($payment->renter_id !== $user->id && $payment->landlord_id !== $user->id) {
            return redirect('/')->with('error', 'You are not authorized to view this payment.');
        }

        return view('payments.show', compact('payment', 'user'));
    }

    /**
     * Upload payment proof
     */
    public function uploadProof(Request $request, $paymentId)
    {
        if (!session('user')) {
            return redirect('/login');
        }

        $user = User::find(session('user')['id']);
        $payment = Payment::where('payment_id', $paymentId)->firstOrFail();

        // Check if user is the renter
        if ($payment->renter_id !== $user->id) {
            return back()->with('error', 'Only the renter can upload payment proof.');
        }

        // Validate request
        $validator = Validator::make($request->all(), [
            'payment_proof' => 'required|file|mimes:jpeg,jpg,png,pdf|max:2048',
            'transaction_id' => 'nullable|string|max:100',
        ], [
            'payment_proof.required' => 'Please upload payment proof.',
            'payment_proof.file' => 'Please upload a valid file.',
            'payment_proof.mimes' => 'Payment proof must be a JPEG, PNG, or PDF file.',
            'payment_proof.max' => 'Payment proof file size must not exceed 2MB.',
            'transaction_id.max' => 'Transaction ID cannot exceed 100 characters.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Upload payment proof
            $file = $request->file('payment_proof');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('payments/proofs', $fileName, 'public');

            // Update payment
            $payment->update([
                'payment_proof' => $filePath,
                'transaction_id' => $request->transaction_id,
                'status' => 'completed',
                'paid_at' => now(),
            ]);

            return redirect()->route('payment.show', $payment->payment_id)
                ->with('success', 'Payment proof uploaded successfully! The landlord will be notified.');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Failed to upload payment proof. Please try again.')
                ->withInput();
        }
    }

    /**
     * Show payment history for user
     */
    public function paymentHistory()
    {
        if (!session('user')) {
            return redirect('/login');
        }

        $user = User::find(session('user')['id']);
        
        if ($user->isRenter()) {
            $payments = Payment::with(['room', 'landlord'])
                ->where('renter_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            $payments = Payment::with(['room', 'renter'])
                ->where('landlord_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        return view('payments.history', compact('payments', 'user'));
    }

    /**
     * Download payment proof
     */
    public function downloadProof($paymentId)
    {
        if (!session('user')) {
            return redirect('/login');
        }

        $user = User::find(session('user')['id']);
        $payment = Payment::where('payment_id', $paymentId)->firstOrFail();

        // Check if user is authorized
        if ($payment->renter_id !== $user->id && $payment->landlord_id !== $user->id) {
            return back()->with('error', 'You are not authorized to download this proof.');
        }

        if (!$payment->payment_proof) {
            return back()->with('error', 'No payment proof available.');
        }

        $filePath = storage_path('app/public/' . $payment->payment_proof);
        
        if (!file_exists($filePath)) {
            return back()->with('error', 'Payment proof file not found.');
        }

        return response()->download($filePath);
    }

    /**
     * Cancel payment
     */
    public function cancelPayment($paymentId)
    {
        if (!session('user')) {
            return redirect('/login');
        }

        $user = User::find(session('user')['id']);
        $payment = Payment::where('payment_id', $paymentId)->firstOrFail();

        // Check if user is the renter
        if ($payment->renter_id !== $user->id) {
            return back()->with('error', 'Only the renter can cancel this payment.');
        }

        // Check if payment can be cancelled
        if ($payment->status !== 'pending') {
            return back()->with('error', 'This payment cannot be cancelled.');
        }

        $payment->update(['status' => 'cancelled']);

        return redirect()->route('payment.history')
            ->with('success', 'Payment cancelled successfully.');
    }
} 