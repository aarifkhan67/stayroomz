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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('renter_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('landlord_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
            $table->string('payment_id')->unique(); // Unique payment identifier
            $table->decimal('amount', 10, 2);
            $table->enum('payment_type', ['rent', 'deposit', 'maintenance', 'other']);
            $table->enum('payment_method', ['upi', 'bank_transfer', 'cash', 'cheque', 'online']);
            $table->enum('status', ['pending', 'completed', 'failed', 'cancelled'])->default('pending');
            $table->text('description')->nullable();
            $table->string('transaction_id')->nullable(); // External transaction ID
            $table->string('qr_code')->nullable(); // QR code for payment
            $table->string('payment_proof')->nullable(); // Payment proof file
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('due_date')->nullable();
            $table->json('payment_details')->nullable(); // Additional payment details
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['renter_id', 'status']);
            $table->index(['landlord_id', 'status']);
            $table->index(['room_id', 'status']);
            $table->index('payment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
}; 