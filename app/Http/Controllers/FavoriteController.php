<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use App\Models\Room;
use App\Models\User;

class FavoriteController extends Controller
{
    /**
     * Add a room to favorites
     */
    public function add(Request $request, $roomId)
    {
        if (!session('user')) {
            return response()->json([
                'success' => false,
                'message' => 'Please login to add favorites'
            ], 401);
        }

        $room = Room::find($roomId);
        if (!$room) {
            return response()->json([
                'success' => false,
                'message' => 'Room not found'
            ], 404);
        }

        $userId = session('user')['id'];
        
        // Check if already favorited
        $existingFavorite = Favorite::where('user_id', $userId)
            ->where('room_id', $roomId)
            ->first();

        if ($existingFavorite) {
            return response()->json([
                'success' => false,
                'message' => 'Room is already in your favorites'
            ], 400);
        }

        // Add to favorites
        Favorite::create([
            'user_id' => $userId,
            'room_id' => $roomId
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Room added to favorites',
            'favorites_count' => User::find($userId)->getFavoritesCount()
        ]);
    }

    /**
     * Remove a room from favorites
     */
    public function remove(Request $request, $roomId)
    {
        if (!session('user')) {
            return response()->json([
                'success' => false,
                'message' => 'Please login to manage favorites'
            ], 401);
        }

        $userId = session('user')['id'];
        
        $favorite = Favorite::where('user_id', $userId)
            ->where('room_id', $roomId)
            ->first();

        if (!$favorite) {
            return response()->json([
                'success' => false,
                'message' => 'Room is not in your favorites'
            ], 404);
        }

        $favorite->delete();

        return response()->json([
            'success' => true,
            'message' => 'Room removed from favorites',
            'favorites_count' => User::find($userId)->getFavoritesCount()
        ]);
    }

    /**
     * Toggle favorite status
     */
    public function toggle(Request $request, $roomId)
    {
        if (!session('user')) {
            return response()->json([
                'success' => false,
                'message' => 'Please login to manage favorites'
            ], 401);
        }

        $userId = session('user')['id'];
        $user = User::find($userId);
        
        $favorite = Favorite::where('user_id', $userId)
            ->where('room_id', $roomId)
            ->first();

        if ($favorite) {
            // Remove from favorites
            $favorite->delete();
            $action = 'removed';
            $message = 'Room removed from favorites';
        } else {
            // Add to favorites
            Favorite::create([
                'user_id' => $userId,
                'room_id' => $roomId
            ]);
            $action = 'added';
            $message = 'Room added to favorites';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'action' => $action,
            'is_favorited' => $action === 'added',
            'favorites_count' => $user->getFavoritesCount()
        ]);
    }

    /**
     * Show user's favorites page
     */
    public function index()
    {
        if (!session('user')) {
            return redirect('/login')->with('error', 'Please login to view your favorites');
        }

        $userId = session('user')['id'];
        $user = User::find($userId);
        
        $favorites = $user->favoritedRooms()
            ->with('reviews')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('favorites', compact('favorites'));
    }

    /**
     * Check if a room is favorited by current user
     */
    public function check($roomId)
    {
        if (!session('user')) {
            return response()->json([
                'is_favorited' => false
            ]);
        }

        $userId = session('user')['id'];
        $isFavorited = Favorite::where('user_id', $userId)
            ->where('room_id', $roomId)
            ->exists();

        return response()->json([
            'is_favorited' => $isFavorited
        ]);
    }
}
