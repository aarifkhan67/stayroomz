<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Room;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Optimize for testing
        $this->withoutMiddleware();
        
        // Use faster hashing for tests
        Hash::setRounds(4);
    }

    /**
     * Create a test user with optimized session setup
     */
    protected function createTestUser($role = 'renter', $attributes = [])
    {
        $user = User::create(array_merge([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '+91 98765 43210',
            'password' => Hash::make('password123'),
            'role' => $role,
            'is_active' => true
        ], $attributes));

        return $user;
    }

    /**
     * Login user with session (optimized for the app's session-based auth)
     */
    protected function loginUser($user)
    {
        session(['user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'role' => $user->role,
        ]]);
        
        return $this;
    }

    /**
     * Create a test room with minimal data
     */
    protected function createTestRoom($owner = null, $attributes = [])
    {
        if (!$owner) {
            $owner = $this->createTestUser('owner');
        }

        return Room::create(array_merge([
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
        ], $attributes));
    }

    /**
     * Mock file upload for faster testing
     */
    protected function mockFileUpload($filename = 'test.jpg', $size = 1024)
    {
        return \Illuminate\Http\UploadedFile::fake()->image($filename, 100, 100)->size($size);
    }
}
