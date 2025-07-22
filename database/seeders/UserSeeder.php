<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a sample room owner
        User::create([
            'name' => 'John Owner',
            'email' => 'owner@stayroomz.com',
            'phone' => '+91 98765 43210',
            'password' => Hash::make('password123'),
            'role' => 'owner',
            'is_active' => true,
        ]);

        // Create a sample renter
        User::create([
            'name' => 'Sarah Renter',
            'email' => 'renter@stayroomz.com',
            'phone' => '+91 98765 43211',
            'password' => Hash::make('password123'),
            'role' => 'renter',
            'is_active' => true,
        ]);

        $this->command->info('Sample users created successfully!');
        $this->command->info('Owner: owner@stayroomz.com / password123');
        $this->command->info('Renter: renter@stayroomz.com / password123');
    }
}
