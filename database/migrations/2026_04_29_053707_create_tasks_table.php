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
//         Schema::create('tasks', function (Blueprint $table) {
//             $table->id();
//             $table->timestamps();
//         });
//     }

//     /**
//      * Reverse the migrations.
//      */
//     public function down(): void
//     {
//         Schema::dropIfExists('tasks');
//     }
// };

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('housekeeper_id')       // assigned housekeeper
                ->constrained('users')
                ->onDelete('cascade');
            $table->foreignId('room_id')              // which room
                ->constrained('rooms')
                ->onDelete('cascade');
            $table->foreignId('request_id')           // linked request (optional)
                ->nullable()
                ->constrained('requests')
                ->onDelete('set null');
            $table->string('title');                  // e.g. "Clean Room 101"
            $table->text('description')->nullable();
            $table->enum('status', [
                'pending',
                'in_progress',
                'completed',
                'cancelled'
            ])->default('pending');
            $table->enum('priority', [
                'low',
                'normal',
                'high',
                'urgent'
            ])->default('normal');
            $table->date('due_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};