<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\RoomReview;
use Illuminate\Support\Facades\Auth;

class RoomReviewController extends Controller
{
    // Store a new review
    public function store(Request $request, $roomId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'guest_name' => 'nullable|string|max:100',
        ]);

        $room = Room::findOrFail($roomId);
        
        // Check if user is logged in
        $user = Auth::user();
        
        if ($user) {
            // Logged in user - check for duplicate review
            $existing = RoomReview::where('room_id', $roomId)->where('user_id', $user->id)->first();
            if ($existing) {
                return response()->json(['success' => false, 'message' => 'You have already reviewed this room.']);
            }
            
            RoomReview::create([
                'room_id' => $roomId,
                'user_id' => $user->id,
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);
        } else {
            // Guest user - check for duplicate by IP address
            $ipAddress = $request->ip();
            $existing = RoomReview::where('room_id', $roomId)
                ->where('ip_address', $ipAddress)
                ->where('created_at', '>', now()->subDays(1)) // Allow one review per day per IP
                ->first();
                
            if ($existing) {
                return response()->json(['success' => false, 'message' => 'You have already reviewed this room recently. Please try again tomorrow.']);
            }
            
            RoomReview::create([
                'room_id' => $roomId,
                'user_id' => null, // Guest user
                'guest_name' => $request->guest_name ?: 'Anonymous',
                'ip_address' => $ipAddress,
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Review submitted successfully!']);
    }

    // Fetch reviews for a room (for API/AJAX)
    public function index($roomId)
    {
        $reviews = RoomReview::where('room_id', $roomId)->with('user')->latest()->get();
        return response()->json($reviews);
    }
} 