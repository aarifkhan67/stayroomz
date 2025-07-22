<?php

namespace Tests\Feature;

use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    public function test_user_can_view_login_page()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    public function test_user_can_view_signup_page()
    {
        $response = $this->get('/signup');
        $response->assertStatus(200);
        $response->assertViewIs('auth.signup');
    }

    public function test_user_can_signup_successfully()
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '+91 98765 43210',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'user_type' => 'renter',
            'terms' => 'on'
        ];

        $response = $this->post('/signup', $userData);
        $response->assertRedirect('/');
        
        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'renter'
        ]);
    }

    public function test_user_can_login_successfully()
    {
        $user = $this->createTestUser('renter');

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
            'user_type' => 'renter'
        ]);

        $response->assertRedirect('/');
    }

    public function test_user_cannot_login_with_invalid_credentials()
    {
        $user = $this->createTestUser('renter');

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
            'user_type' => 'renter'
        ]);

        $response->assertSessionHasErrors();
    }

    public function test_user_can_logout()
    {
        $user = $this->createTestUser('renter');
        $this->loginUser($user);
        
        $response = $this->get('/logout');
        $response->assertRedirect('/');
    }

    public function test_user_can_view_profile_when_logged_in()
    {
        $user = $this->createTestUser('renter');
        $this->loginUser($user);
        
        $response = $this->get('/profile');
        $response->assertStatus(200);
        $response->assertViewIs('auth.profile');
    }

    public function test_user_cannot_view_profile_when_not_logged_in()
    {
        $response = $this->get('/profile');
        $response->assertRedirect('/login');
    }

    public function test_user_can_update_profile()
    {
        $user = $this->createTestUser('renter');
        $this->loginUser($user);
        
        $response = $this->post('/profile/update', [
            'name' => 'Updated Name',
            'phone' => '+91 98765 43211'
        ]);

        $response->assertRedirect();
        
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'phone' => '+91 98765 43211'
        ]);
    }
} 