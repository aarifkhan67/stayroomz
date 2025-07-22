<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Room;
use App\Models\RoomReview;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class ReviewSystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_add_review_to_room()
    {
        $owner = User::create([
            'name' => 'Room Owner',
            'email' => 'owner@example.com',
            'phone' => '+91 98765 43210',
            'password' => Hash::make('password123'),
            'role' => 'owner',
            'is_active' => true
        ]);

        $renter = User::create([
            'name' => 'Room Renter',
            'email' => 'renter@example.com',
            'phone' => '+91 98765 43211',
            'password' => Hash::make('password123'),
            'role' => 'renter',
            'is_active' => true
        ]);

        $room = Room::create([
            'user_id' => $owner->id,
            'title' => 'Test Room',
            'type' => 'single',
            'location' => 'City Center',
            'address' => '123 Main Street',
            'price' => 5000,
            'availability' => 'available',
            'available_from' => '2025-01-01',
            'description' => 'Test room',
            'occupant_type' => 'student',
            'amenities' => ['wifi'],
            'owner_name' => 'John Owner',
            'phone' => '+91 98765 43210',
            'email' => 'owner@example.com',
            'preferred_contact' => 'phone'
        ]);

        $this->actingAs($renter);

        $reviewData = [
            'rating' => 5,
            'comment' => 'Great room, very clean and comfortable!',
            'user_name' => 'Test Renter'
        ];

        $response = $this->post("/rooms/{$room->id}/review", $reviewData);
        $response->assertRedirect()->assertSessionHas('success');

        $this->assertDatabaseHas('room_reviews', [
            'room_id' => $room->id,
            'user_id' => $renter->id,
            'rating' => 5,
            'comment' => 'Great room, very clean and comfortable!'
        ]);
    }

    public function test_user_can_view_room_reviews()
    {
        $owner = User::create([
            'name' => 'Room Owner',
            'email' => 'owner@example.com',
            'phone' => '+91 98765 43210',
            'password' => Hash::make('password123'),
            'role' => 'owner',
            'is_active' => true
        ]);

        $renter = User::create([
            'name' => 'Room Renter',
            'email' => 'renter@example.com',
            'phone' => '+91 98765 43211',
            'password' => Hash::make('password123'),
            'role' => 'renter',
            'is_active' => true
        ]);

        $room = Room::create([
            'user_id' => $owner->id,
            'title' => 'Test Room',
            'type' => 'single',
            'location' => 'City Center',
            'address' => '123 Main Street',
            'price' => 5000,
            'availability' => 'available',
            'available_from' => '2025-01-01',
            'description' => 'Test room',
            'occupant_type' => 'student',
            'amenities' => ['wifi'],
            'owner_name' => 'John Owner',
            'phone' => '+91 98765 43210',
            'email' => 'owner@example.com',
            'preferred_contact' => 'phone'
        ]);

        RoomReview::create([
            'room_id' => $room->id,
            'user_id' => $renter->id,
            'rating' => 5,
            'comment' => 'Great room!',
            'user_name' => 'Test Renter'
        ]);

        $response = $this->get("/rooms/{$room->id}/reviews");
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'reviews' => [
                '*' => [
                    'id',
                    'rating',
                    'comment',
                    'user_name',
                    'created_at'
                ]
            ]
        ]);
    }

    public function test_review_validation_works()
    {
        $owner = User::create([
            'name' => 'Room Owner',
            'email' => 'owner@example.com',
            'phone' => '+91 98765 43210',
            'password' => Hash::make('password123'),
            'role' => 'owner',
            'is_active' => true
        ]);

        $renter = User::create([
            'name' => 'Room Renter',
            'email' => 'renter@example.com',
            'phone' => '+91 98765 43211',
            'password' => Hash::make('password123'),
            'role' => 'renter',
            'is_active' => true
        ]);

        $room = Room::create([
            'user_id' => $owner->id,
            'title' => 'Test Room',
            'type' => 'single',
            'location' => 'City Center',
            'address' => '123 Main Street',
            'price' => 5000,
            'availability' => 'available',
            'available_from' => '2025-01-01',
            'description' => 'Test room',
            'occupant_type' => 'student',
            'amenities' => ['wifi'],
            'owner_name' => 'John Owner',
            'phone' => '+91 98765 43210',
            'email' => 'owner@example.com',
            'preferred_contact' => 'phone'
        ]);

        $this->actingAs($renter);

        // Test with invalid rating
        $response = $this->post("/rooms/{$room->id}/review", [
            'rating' => 6, // Invalid rating (should be 1-5)
            'comment' => 'Test comment',
            'user_name' => 'Test Renter'
        ]);

        $response->assertSessionHasErrors();

        // Test with missing required fields
        $response = $this->post("/rooms/{$room->id}/review", [
            'rating' => 5
            // Missing comment and user_name
        ]);

        $response->assertSessionHasErrors();
    }

    public function test_room_average_rating_calculation()
    {
        $owner = User::create([
            'name' => 'Room Owner',
            'email' => 'owner@example.com',
            'phone' => '+91 98765 43210',
            'password' => Hash::make('password123'),
            'role' => 'owner',
            'is_active' => true
        ]);

        $renter1 = User::create([
            'name' => 'Renter 1',
            'email' => 'renter1@example.com',
            'phone' => '+91 98765 43211',
            'password' => Hash::make('password123'),
            'role' => 'renter',
            'is_active' => true
        ]);

        $renter2 = User::create([
            'name' => 'Renter 2',
            'email' => 'renter2@example.com',
            'phone' => '+91 98765 43212',
            'password' => Hash::make('password123'),
            'role' => 'renter',
            'is_active' => true
        ]);

        $room = Room::create([
            'user_id' => $owner->id,
            'title' => 'Test Room',
            'type' => 'single',
            'location' => 'City Center',
            'address' => '123 Main Street',
            'price' => 5000,
            'availability' => 'available',
            'available_from' => '2025-01-01',
            'description' => 'Test room',
            'occupant_type' => 'student',
            'amenities' => ['wifi'],
            'owner_name' => 'John Owner',
            'phone' => '+91 98765 43210',
            'email' => 'owner@example.com',
            'preferred_contact' => 'phone'
        ]);

        // Add reviews
        RoomReview::create([
            'room_id' => $room->id,
            'user_id' => $renter1->id,
            'rating' => 5,
            'comment' => 'Great room!',
            'user_name' => 'Renter 1'
        ]);

        RoomReview::create([
            'room_id' => $room->id,
            'user_id' => $renter2->id,
            'rating' => 3,
            'comment' => 'Good room',
            'user_name' => 'Renter 2'
        ]);

        // Test average rating calculation
        $averageRating = $room->averageRating();
        $this->assertEquals(4.0, $averageRating); // (5 + 3) / 2 = 4.0
    }
} 