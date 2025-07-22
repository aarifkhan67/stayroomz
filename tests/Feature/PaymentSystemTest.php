<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Room;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PaymentSystemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_user_can_view_payment_form()
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

        $response = $this->get("/payment/{$room->id}/create");
        $response->assertStatus(200);
        $response->assertViewIs('payments.create');
        $response->assertSee('Test Room');
    }

    public function test_user_can_create_payment()
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

        $paymentData = [
            'amount' => 5000,
            'payment_method' => 'bank_transfer',
            'payment_details' => 'Test payment details',
            'contact_number' => '+91 98765 43211',
            'email' => 'renter@example.com'
        ];

        $response = $this->post("/payment/{$room->id}/create", $paymentData);
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('payments', [
            'room_id' => $room->id,
            'user_id' => $renter->id,
            'amount' => 5000,
            'payment_method' => 'bank_transfer',
            'status' => 'pending'
        ]);
    }

    public function test_user_can_upload_payment_proof()
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

        $payment = Payment::create([
            'room_id' => $room->id,
            'user_id' => $renter->id,
            'amount' => 5000,
            'payment_method' => 'bank_transfer',
            'payment_details' => 'Test payment',
            'contact_number' => '+91 98765 43211',
            'email' => 'renter@example.com',
            'status' => 'pending'
        ]);

        $this->actingAs($renter);

        $file = UploadedFile::fake()->image('payment_proof.jpg');

        $response = $this->post("/payment/{$payment->id}/proof", [
            'payment_proof' => $file
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'status' => 'pending_verification'
        ]);
    }

    public function test_user_can_view_payment_details()
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

        $payment = Payment::create([
            'room_id' => $room->id,
            'user_id' => $renter->id,
            'amount' => 5000,
            'payment_method' => 'bank_transfer',
            'payment_details' => 'Test payment',
            'contact_number' => '+91 98765 43211',
            'email' => 'renter@example.com',
            'status' => 'pending'
        ]);

        $this->actingAs($renter);

        $response = $this->get("/payment/{$payment->id}");
        $response->assertStatus(200);
        $response->assertViewIs('payments.show');
        $response->assertSee('Test Room');
        $response->assertSee('5000');
    }

    public function test_user_can_cancel_payment()
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

        $payment = Payment::create([
            'room_id' => $room->id,
            'user_id' => $renter->id,
            'amount' => 5000,
            'payment_method' => 'bank_transfer',
            'payment_details' => 'Test payment',
            'contact_number' => '+91 98765 43211',
            'email' => 'renter@example.com',
            'status' => 'pending'
        ]);

        $this->actingAs($renter);

        $response = $this->get("/payment/{$payment->id}/cancel");
        $response->assertRedirect('/payments');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'status' => 'cancelled'
        ]);
    }

    public function test_user_can_view_payment_history()
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

        Payment::create([
            'room_id' => $room->id,
            'user_id' => $renter->id,
            'amount' => 5000,
            'payment_method' => 'bank_transfer',
            'payment_details' => 'Test payment 1',
            'contact_number' => '+91 98765 43211',
            'email' => 'renter@example.com',
            'status' => 'completed'
        ]);

        Payment::create([
            'room_id' => $room->id,
            'user_id' => $renter->id,
            'amount' => 3000,
            'payment_method' => 'cash',
            'payment_details' => 'Test payment 2',
            'contact_number' => '+91 98765 43211',
            'email' => 'renter@example.com',
            'status' => 'pending'
        ]);

        $this->actingAs($renter);

        $response = $this->get('/payments');
        $response->assertStatus(200);
        $response->assertViewIs('payments.history');
        $response->assertSee('Test Room');
        $response->assertSee('5000');
        $response->assertSee('3000');
    }
} 