<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class RoomManagementTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_owner_can_view_list_room_page()
    {
        $owner = $this->createTestUser('owner');
        $this->loginUser($owner);
        
        $response = $this->get('/list-room');
        $response->assertStatus(200);
        $response->assertViewIs('list-room');
    }

    public function test_renter_cannot_view_list_room_page()
    {
        $renter = $this->createTestUser('renter');
        $this->loginUser($renter);
        
        $response = $this->get('/list-room');
        $response->assertRedirect('/');
        $response->assertSessionHas('error', 'Only room owners can list rooms');
    }

    public function test_owner_can_add_room_successfully()
    {
        $owner = $this->createTestUser('owner');
        $this->loginUser($owner);

        $roomData = [
            'title' => 'Beautiful Room in City Center',
            'type' => 'single',
            'location' => 'City Center',
            'address' => '123 Main Street',
            'price' => 5000,
            'deposit' => 10000,
            'availability' => 'available',
            'available_from' => '2025-01-01',
            'description' => 'A beautiful room with all amenities',
            'occupant_type' => 'student',
            'amenities' => ['wifi', 'ac', 'parking'],
            'owner_name' => 'John Owner',
            'phone' => '+91 98765 43210',
            'email' => 'owner@example.com',
            'preferred_contact' => 'phone'
        ];

        $response = $this->post('/add-room', $roomData);
        $response->assertRedirect('/my-rooms');
        $response->assertSessionHas('success', 'Room added successfully!');

        $this->assertDatabaseHas('rooms', [
            'title' => 'Beautiful Room in City Center',
            'type' => 'single',
            'price' => 5000,
            'user_id' => $owner->id
        ]);
    }

    public function test_owner_can_add_room_with_image()
    {
        $owner = $this->createTestUser('owner');
        $this->loginUser($owner);

        $file = $this->mockFileUpload('room.jpg');

        $roomData = [
            'title' => 'Beautiful Room with Image',
            'type' => 'single',
            'location' => 'City Center',
            'address' => '123 Main Street',
            'price' => 5000,
            'availability' => 'available',
            'available_from' => '2025-01-01',
            'description' => 'A beautiful room with all amenities',
            'occupant_type' => 'student',
            'amenities' => ['wifi', 'ac'],
            'owner_name' => 'John Owner',
            'phone' => '+91 98765 43210',
            'email' => 'owner@example.com',
            'preferred_contact' => 'phone',
            'main_image' => $file
        ];

        $response = $this->post('/add-room', $roomData);
        $response->assertRedirect('/my-rooms');

        $this->assertDatabaseHas('rooms', [
            'title' => 'Beautiful Room with Image',
            'user_id' => $owner->id
        ]);
    }

    public function test_owner_can_view_my_rooms()
    {
        $owner = $this->createTestUser('owner');
        $this->loginUser($owner);
        
        $room = $this->createTestRoom($owner);

        $response = $this->get('/my-rooms');
        $response->assertStatus(200);
        $response->assertViewIs('my-rooms');
        $response->assertSee('Test Room');
    }

    public function test_owner_can_delete_room()
    {
        $owner = $this->createTestUser('owner');
        $this->loginUser($owner);
        
        $room = $this->createTestRoom($owner);
        
        $response = $this->post("/delete-room/{$room->id}");
        $response->assertJson(['success' => true]);

        $this->assertDatabaseMissing('rooms', ['id' => $room->id]);
    }

    public function test_owner_can_update_room()
    {
        $owner = $this->createTestUser('owner');
        $this->loginUser($owner);
        
        $room = $this->createTestRoom($owner);
        
        $response = $this->post("/update-room/{$room->id}", [
            'title' => 'Updated Room Title',
            'price' => 6000
        ]);
        
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('rooms', [
            'id' => $room->id,
            'title' => 'Updated Room Title',
            'price' => 6000
        ]);
    }

    public function test_home_page_shows_available_rooms()
    {
        $owner = $this->createTestUser('owner');

        $this->createTestRoom($owner, ['title' => 'Available Room']);
        $this->createTestRoom($owner, ['title' => 'Rented Room', 'availability' => 'rented']);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertViewIs('home');
        $response->assertSee('Available Room');
        $response->assertDontSee('Rented Room');
    }
} 