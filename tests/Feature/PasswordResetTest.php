<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_forgot_password_page()
    {
        $response = $this->get('/forgot-password');
        $response->assertStatus(200);
        $response->assertViewIs('auth.forgot-password');
    }

    public function test_user_can_request_password_reset()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '+91 98765 43210',
            'password' => Hash::make('password123'),
            'role' => 'renter',
            'is_active' => true
        ]);

        $response = $this->post('/forgot-password', [
            'email' => 'test@example.com'
        ]);

        $response->assertRedirect('/forgot-password');
        $response->assertSessionHas('success');
    }

    public function test_password_reset_request_validation()
    {
        // Test with non-existent email
        $response = $this->post('/forgot-password', [
            'email' => 'nonexistent@example.com'
        ]);

        $response->assertSessionHasErrors();

        // Test with invalid email format
        $response = $this->post('/forgot-password', [
            'email' => 'invalid-email'
        ]);

        $response->assertSessionHasErrors(['email']);

        // Test with empty email
        $response = $this->post('/forgot-password', [
            'email' => ''
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_user_can_view_reset_password_form()
    {
        $token = 'test-reset-token';
        
        $response = $this->get("/reset-password/{$token}");
        $response->assertStatus(200);
        $response->assertViewIs('auth.reset-password');
    }

    public function test_user_can_reset_password()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '+91 98765 43210',
            'password' => Hash::make('oldpassword'),
            'role' => 'renter',
            'is_active' => true
        ]);

        $token = 'test-reset-token';

        $response = $this->post('/reset-password', [
            'token' => $token,
            'email' => 'test@example.com',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123'
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHas('success');
    }

    public function test_password_reset_validation()
    {
        $token = 'test-reset-token';

        // Test with mismatched passwords
        $response = $this->post('/reset-password', [
            'token' => $token,
            'email' => 'test@example.com',
            'password' => 'newpassword123',
            'password_confirmation' => 'differentpassword'
        ]);

        $response->assertSessionHasErrors(['password']);

        // Test with short password
        $response = $this->post('/reset-password', [
            'token' => $token,
            'email' => 'test@example.com',
            'password' => '123',
            'password_confirmation' => '123'
        ]);

        $response->assertSessionHasErrors(['password']);

        // Test with missing fields
        $response = $this->post('/reset-password', [
            'token' => $token,
            'email' => 'test@example.com'
            // Missing password and password_confirmation
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    public function test_reset_password_with_invalid_token()
    {
        $response = $this->post('/reset-password', [
            'token' => 'invalid-token',
            'email' => 'test@example.com',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123'
        ]);

        $response->assertSessionHasErrors();
    }

    public function test_reset_password_with_non_existent_email()
    {
        $response = $this->post('/reset-password', [
            'token' => 'test-token',
            'email' => 'nonexistent@example.com',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123'
        ]);

        $response->assertSessionHasErrors();
    }
} 