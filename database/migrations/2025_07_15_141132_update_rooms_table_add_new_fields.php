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
        Schema::table('rooms', function (Blueprint $table) {
            // Add user_id foreign key if it doesn't exist
            if (!Schema::hasColumn('rooms', 'user_id')) {
                $table->foreignId('user_id')->constrained()->onDelete('cascade')->after('id');
            }
            
            // Add new fields if they don't exist
            if (!Schema::hasColumn('rooms', 'type')) {
                $table->enum('type', ['single', 'shared', 'studio', '1bhk', '2bhk', '3bhk'])->after('user_id');
            }
            if (!Schema::hasColumn('rooms', 'location')) {
                $table->string('location')->after('type');
            }
            if (!Schema::hasColumn('rooms', 'deposit')) {
                $table->decimal('deposit', 10, 2)->nullable()->after('price');
            }
            if (!Schema::hasColumn('rooms', 'available_from')) {
                $table->date('available_from')->nullable()->after('availability');
            }
            if (!Schema::hasColumn('rooms', 'occupant_type')) {
                $table->enum('occupant_type', ['student', 'professional', 'family', 'couple', 'single'])->nullable()->after('description');
            }
            if (!Schema::hasColumn('rooms', 'amenities')) {
                $table->json('amenities')->nullable()->after('occupant_type');
            }
            if (!Schema::hasColumn('rooms', 'owner_name')) {
                $table->string('owner_name')->after('amenities');
            }
            if (!Schema::hasColumn('rooms', 'phone')) {
                $table->string('phone')->after('owner_name');
            }
            if (!Schema::hasColumn('rooms', 'email')) {
                $table->string('email')->after('phone');
            }
            if (!Schema::hasColumn('rooms', 'preferred_contact')) {
                $table->enum('preferred_contact', ['phone', 'email', 'both'])->default('phone')->after('email');
            }
            if (!Schema::hasColumn('rooms', 'image')) {
                $table->string('image')->nullable()->after('preferred_contact');
            }
            if (!Schema::hasColumn('rooms', 'additional_images')) {
                $table->json('additional_images')->nullable()->after('image');
            }
            if (!Schema::hasColumn('rooms', 'views')) {
                $table->integer('views')->default(0)->after('additional_images');
            }
            if (!Schema::hasColumn('rooms', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('views');
            }
        });
        
        // Add indexes for better performance
        Schema::table('rooms', function (Blueprint $table) {
            $table->index(['location', 'availability']);
            $table->index(['price']);
            $table->index(['type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            // Drop indexes
            $table->dropIndex(['location', 'availability']);
            $table->dropIndex(['price']);
            $table->dropIndex(['type']);
            
            // Drop columns
            $table->dropColumn([
                'user_id', 'type', 'location', 'deposit', 'available_from',
                'occupant_type', 'amenities', 'owner_name', 'phone', 'email',
                'preferred_contact', 'image', 'additional_images', 'views', 'is_featured'
            ]);
        });
    }
};
