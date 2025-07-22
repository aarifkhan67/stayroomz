<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomReviewController;

// Function to get default image for room type
if (!function_exists('getRoomImage')) {
    function getRoomImage($roomType) {
        // Return a simple default image - users should upload their own images
        return '/images/default-room.jpg';
    }
}

Route::get('/', function () {
    // Load rooms from database
    $rooms = \App\Models\Room::with('reviews')->get();

    // Filter out rented rooms - only show available and coming-soon rooms
    $availableRooms = $rooms->filter(function($room) {
        return $room->availability === 'available' || $room->availability === 'coming-soon';
    })->map(function($room) {
        // Add average rating to room data
        $room->average_rating = $room->averageRating();
        return $room;
    });
    
    return view('home', compact('rooms', 'availableRooms'));
});

Route::get('/about', function () {
    return view('about');
});

Route::get('/contact', [App\Http\Controllers\ContactController::class, 'show']);
Route::post('/contact', [App\Http\Controllers\ContactController::class, 'submit']);

// Authentication Routes
Route::get('/login', [App\Http\Controllers\AuthController::class, 'showLogin']);
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
Route::get('/signup', [App\Http\Controllers\AuthController::class, 'showSignup']);
Route::post('/signup', [App\Http\Controllers\AuthController::class, 'signup']);
Route::get('/logout', [App\Http\Controllers\AuthController::class, 'logout']);
Route::get('/profile', function() {
    if (!session('user')) {
        return redirect('/login');
    }
    $user = \App\Models\User::find(session('user')['id']);
    return view('auth.profile', compact('user'));
});
Route::post('/profile/update', [App\Http\Controllers\AuthController::class, 'updateProfile']);



// Protected Routes
Route::get('/list-room', function () {
    if (!session('user')) {
        return redirect('/login')->with('error', 'Please login to list your room');
    }
    
    $user = \App\Models\User::find(session('user')['id']);
    if (!$user->canListRooms()) {
        return redirect('/')->with('error', 'Only room owners can list rooms');
    }
    
    return view('list-room');
});

Route::post('/add-room', function () {
    // Check if user is logged in
    if (!session('user')) {
        return redirect('/login')->with('error', 'Please login to add a room');
    }
    

    
    try {
        // Handle image upload
        $imagePath = null;
        $additionalImages = [];
        
        // Handle main image
        if (request()->hasFile('main_image') && request()->file('main_image')->isValid()) {
            $file = request()->file('main_image');
            
            // Validate file type
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            if (!in_array($file->getMimeType(), $allowedTypes)) {
                return back()->withErrors(['main_image' => 'Please upload a valid image file (JPEG, PNG, GIF)']);
            }
            
            // Validate file size (10MB max)
            if ($file->getSize() > 10 * 1024 * 1024) {
                return back()->withErrors(['main_image' => 'Image size should be less than 10MB']);
            }
            
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/rooms'), $fileName);
            $imagePath = '/uploads/rooms/' . $fileName;
        } else {
            // Fallback to default image if no upload
            $imagePath = getRoomImage(request('type'));
        }
        
        // Handle additional images
        if (request()->hasFile('additional_images')) {
            $files = request()->file('additional_images');
            foreach ($files as $file) {
                if ($file->isValid()) {
                    // Validate file type
                    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                    if (!in_array($file->getMimeType(), $allowedTypes)) {
                        continue; // Skip invalid files
                    }
                    
                    // Validate file size (10MB max)
                    if ($file->getSize() > 10 * 1024 * 1024) {
                        continue; // Skip oversized files
                    }
                    
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $file->move(public_path('uploads/rooms'), $fileName);
                    $additionalImages[] = '/uploads/rooms/' . $fileName;
                }
            }
        }
        
        // Create room in database
        $room = new \App\Models\Room();
        $room->user_id = session('user')['id'];
        $room->title = request('title');
        $room->type = request('type');
        $room->location = request('location');
        $room->address = request('address');
        $room->price = (int) request('price');
        $room->deposit = request('deposit') ? (int) request('deposit') : null;
        $room->availability = request('availability');
        $room->available_from = request('available_from');
        $room->description = request('description');
        $room->occupant_type = request('occupant_type');
        $room->amenities = request('amenities', []);
        $room->owner_name = request('owner_name');
        $room->phone = request('phone');
        $room->email = request('email');
        $room->preferred_contact = request('preferred_contact');
        $room->image = $imagePath;
        $room->additional_images = $additionalImages;
        $room->save();
        

        
        return redirect('/my-rooms')->with('success', 'Room added successfully!');
    } catch (Exception $e) {
        return back()->withErrors(['error' => 'An error occurred while adding the room. Please try again.']);
    }
});

Route::get('/dashboard', function () {
    if (!session('user')) {
        return redirect('/login');
    }
    
    $user = \App\Models\User::find(session('user')['id']);
    if (!$user->canListRooms()) {
        return redirect('/')->with('error', 'Only room owners can access dashboard');
    }
    
    // Load rooms from database for current user
    $userRooms = \App\Models\Room::where('user_id', session('user')['id'])->get();
    
    return view('dashboard', compact('userRooms'));
});

Route::get('/my-rooms', function () {
    if (!session('user')) {
        return redirect('/login');
    }
    
    $user = \App\Models\User::find(session('user')['id']);
    if (!$user->canListRooms()) {
        return redirect('/')->with('error', 'Only room owners can access my rooms');
    }
    
    // Load rooms from database for current user
    $userRooms = \App\Models\Room::where('user_id', session('user')['id'])->get();
    
    return view('my-rooms', compact('userRooms'));
});





Route::post('/delete-room/{id}', function ($id) {
    if (!session('user')) {
        return redirect('/login');
    }
    
    // Delete room from database
    $room = \App\Models\Room::where('id', $id)
        ->where('user_id', session('user')['id'])
        ->first();
    
    if ($room) {
        $room->delete();
        return response()->json(['success' => true]);
    }
    
    return response()->json(['success' => false, 'message' => 'Room not found']);
});

Route::post('/update-room/{id}', function ($id) {
    if (!session('user')) {
        return redirect('/login');
    }
    
    // Update room in database
    $room = \App\Models\Room::where('id', $id)
        ->where('user_id', session('user')['id'])
        ->first();
    
    if ($room) {
        $updateData = request()->all();
        
        // Update image if room type changed
        if (isset($updateData['type']) && $updateData['type'] !== $room->type) {
            $updateData['image'] = getRoomImage($updateData['type']);
        }
        
        $room->update($updateData);
        return response()->json(['success' => true]);
    }
    
    return response()->json(['success' => false, 'message' => 'Room not found']);
});

Route::post('/rooms/{room}/review', [RoomReviewController::class, 'store']);
Route::get('/rooms/{room}/reviews', [RoomReviewController::class, 'index']);

// Favorites Routes
Route::post('/favorites/{room}/toggle', [App\Http\Controllers\FavoriteController::class, 'toggle']);
Route::get('/favorites', [App\Http\Controllers\FavoriteController::class, 'index']);
Route::get('/favorites/{room}/check', [App\Http\Controllers\FavoriteController::class, 'check']);

// Password Reset Routes
Route::get('/forgot-password', [App\Http\Controllers\PasswordResetController::class, 'showResetRequestForm']);
Route::post('/forgot-password', [App\Http\Controllers\PasswordResetController::class, 'sendResetLink']);
Route::get('/reset-password/{token}', [App\Http\Controllers\PasswordResetController::class, 'showResetForm']);
Route::post('/reset-password', [App\Http\Controllers\PasswordResetController::class, 'resetPassword']);

// Payment Routes
Route::get('/payment/{room}/create', [App\Http\Controllers\PaymentController::class, 'showPaymentForm'])->name('payment.create-form');
Route::post('/payment/{room}/create', [App\Http\Controllers\PaymentController::class, 'createPayment'])->name('payment.create');
Route::get('/payment/{paymentId}', [App\Http\Controllers\PaymentController::class, 'showPayment'])->name('payment.show');
Route::post('/payment/{paymentId}/proof', [App\Http\Controllers\PaymentController::class, 'uploadProof'])->name('payment.upload-proof');
Route::get('/payment/{paymentId}/download', [App\Http\Controllers\PaymentController::class, 'downloadProof'])->name('payment.download-proof');
Route::get('/payment/{paymentId}/cancel', [App\Http\Controllers\PaymentController::class, 'cancelPayment'])->name('payment.cancel');
Route::get('/payments', [App\Http\Controllers\PaymentController::class, 'paymentHistory'])->name('payment.history');
