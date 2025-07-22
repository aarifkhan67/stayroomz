<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Room;
use App\Models\Favorite;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class FavoriteSystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_add_room_to_favorites()
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

        $response = $this->post("/favorites/{$room->id}/toggle");
        $response->assertJson(['is_favorite' => true]);

        $this->assertDatabaseHas('favorites', [
            'user_id' => $renter->id,
            'room_id' => $room->id
        ]);
    }

    public function test_user_can_remove_room_from_favorites()
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

        // First add to favorites
        Favorite::create([
            'user_id' => $renter->id,
            'room_id' => $room->id
        ]);

        $this->actingAs($renter);

        // Then remove from favorites
        $response = $this->post("/favorites/{$room->id}/toggle");
        $response->assertJson(['is_favorite' => false]);

        $this->assertDatabaseMissing('favorites', [
            'user_id' => $renter->id,
            'room_id' => $room->id
        ]);
    }

    public function test_user_can_check_if_room_is_favorite()
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

        // Check when not favorite
        $response = $this->get("/favorites/{$room->id}/check");
        $response->assertJson(['is_favorite' => false]);

        // Add to favorites
        Favorite::create([
            'user_id' => $renter->id,
            'room_id' => $room->id
        ]);

        // Check when favorite
        $response = $this->get("/favorites/{$room->id}/check");
        $response->assertJson(['is_favorite' => true]);
    }

    public function test_user_can_view_favorites_page()
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

        $room1 = Room::create([
            'user_id' => $owner->id,
            'title' => 'Test Room 1',
            'type' => 'single',
            'location' => 'City Center',
            'address' => '123 Main Street',
            'price' => 5000,
            'availability' => 'available',
            'available_from' => '2025-01-01',
            'description' => 'Test room 1',
            'occupant_type' => 'student',
            'amenities' => ['wifi'],
            'owner_name' => 'John Owner',
            'phone' => '+91 98765 43210',
            'email' => 'owner@example.com',
            'preferred_contact' => 'phone'
        ]);

        $room2 = Room::create([
            'user_id' => $owner->id,
            'title' => 'Test Room 2',
            'type' => 'double',
            'location' => 'Suburb',
            'address' => '456 Suburb Street',
            'price' => 7000,
            'availability' => 'available',
            'available_from' => '2025-01-01',
            'description' => 'Test room 2',
            'occupant_type' => 'professional',
            'amenities' => ['wifi', 'ac'],
            'owner_name' => 'John Owner',
            'phone' => '+91 98765 43210',
            'email' => 'owner@example.com',
            'preferred_contact' => 'phone'
        ]);

        // Add rooms to favorites
        Favorite::create([
            'user_id' => $renter->id,
            'room_id' => $room1->id
        ]);

        Favorite::create([
            'user_id' => $renter->id,
            'room_id' => $room2->id
        ]);

        $this->actingAs($renter);

        $response = $this->get('/favorites');
        $response->assertStatus(200);
        $response->assertViewIs('favorites');
        $response->assertSee('Test Room 1');
        $response->assertSee('Test Room 2');
    }

    public function test_favorites_page_shows_only_user_favorites()
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

        // Add to favorites for both users
        Favorite::create([
            'user_id' => $renter1->id,
            'room_id' => $room->id
        ]);

        Favorite::create([
            'user_id' => $renter2->id,
            'room_id' => $room->id
        ]);

        $this->actingAs($renter1);

        $response = $this->get('/favorites');
        $response->assertStatus(200);
        $response->assertSee('Test Room');

        // Verify only one favorite is shown (for current user)
        $this->assertEquals(1, $response->viewData('favorites')->count());
    }
} 