<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->string('type'); // single, shared, studio, 1bhk, 2bhk, 3bhk
            $table->string('location'); // city
            $table->text('address');
            $table->decimal('price', 10, 2);
            $table->decimal('deposit', 10, 2)->nullable();
            $table->enum('availability', ['available', 'rented', 'coming-soon'])->default('available');
            $table->date('available_from')->nullable();
            $table->string('occupant_type')->nullable(); // student, professional, family, couple, single
            $table->json('amenities')->nullable(); // array of amenities
            $table->string('owner_name');
            $table->string('phone');
            $table->string('email');
            $table->enum('preferred_contact', ['phone', 'email', 'both'])->default('phone');
            $table->string('main_image')->nullable();
            $table->json('additional_images')->nullable();
            $table->integer('views')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
