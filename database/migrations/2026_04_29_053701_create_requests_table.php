<?php

// use Illuminate\Database\Migrations\Migration;
// use Illuminate\Database\Schema\Blueprint;
// use Illuminate\Support\Facades\Schema;

// return new class extends Migration
// {
//     /**
//      * Run the migrations.
//      */
//     public function up(): void
//     {
//         Schema::create('requests', function (Blueprint $table) {
//             $table->id();
//             $table->timestamps();
//         });
//     }

//     /**
//      * Reverse the migrations.
//      */
//     public function down(): void
//     {
//         Schema::dropIfExists('requests');
//     }
// };

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guest_id')             // which guest submitted
                ->constrained('users')
                ->onDelete('cascade');
            $table->foreignId('room_id')              // which room it's for
                ->constrained('rooms')
                ->onDelete('cascade');
            $table->string('request_type');           // e.g. "Cleaning", "Towels", "Room Service"
            $table->text('description')->nullable();  // additional details
            $table->enum('status', [
                'pending',
                'reviewed',
                'in_progress',
                'completed',
                'cancelled'
            ])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};