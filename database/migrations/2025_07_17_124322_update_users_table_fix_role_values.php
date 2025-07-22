<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, update any existing 'user' roles to 'renter' and 'admin' roles to 'owner'
        DB::table('users')->where('role', 'user')->update(['role' => 'renter']);
        DB::table('users')->where('role', 'admin')->update(['role' => 'owner']);

        // Drop the existing role column
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });

        // Recreate the role column with correct enum values
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['owner', 'renter'])->default('renter')->after('password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Update roles back to original values
        DB::table('users')->where('role', 'renter')->update(['role' => 'user']);
        DB::table('users')->where('role', 'owner')->update(['role' => 'admin']);

        // Drop the role column
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });

        // Recreate with original enum values
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['user', 'admin'])->default('user')->after('password');
        });
    }
};
