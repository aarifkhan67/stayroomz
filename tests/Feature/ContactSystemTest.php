<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ContactSystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_contact_page()
    {
        $response = $this->get('/contact');
        $response->assertStatus(200);
        $response->assertViewIs('contact');
    }

    public function test_user_can_submit_contact_form()
    {
        $contactData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '+91 98765 43210',
            'subject' => 'General Inquiry',
            'message' => 'This is a test message for contact form.'
        ];

        $response = $this->post('/contact', $contactData);
        $response->assertRedirect('/contact');
        $response->assertSessionHas('success');
    }

    public function test_contact_form_validation_works()
    {
        // Test with missing required fields
        $response = $this->post('/contact', [
            'name' => 'John Doe'
            // Missing email, phone, subject, message
        ]);

        $response->assertSessionHasErrors(['email', 'phone', 'subject', 'message']);

        // Test with invalid email
        $response = $this->post('/contact', [
            'name' => 'John Doe',
            'email' => 'invalid-email',
            'phone' => '+91 98765 43210',
            'subject' => 'Test Subject',
            'message' => 'Test message'
        ]);

        $response->assertSessionHasErrors(['email']);

        // Test with invalid phone
        $response = $this->post('/contact', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => 'invalid-phone',
            'subject' => 'Test Subject',
            'message' => 'Test message'
        ]);

        $response->assertSessionHasErrors(['phone']);
    }

    public function test_contact_form_requires_all_fields()
    {
        $response = $this->post('/contact', []);
        $response->assertSessionHasErrors(['name', 'email', 'phone', 'subject', 'message']);
    }

    public function test_contact_form_accepts_valid_data()
    {
        $validData = [
            'name' => 'Jane Smith',
            'email' => 'jane.smith@example.com',
            'phone' => '+91 98765 43211',
            'subject' => 'Room Availability',
            'message' => 'I would like to know about room availability in your area.'
        ];

        $response = $this->post('/contact', $validData);
        $response->assertRedirect('/contact');
        $response->assertSessionHas('success');
    }
} 