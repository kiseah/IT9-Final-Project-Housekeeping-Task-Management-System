<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('request_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')
                  ->constrained('requests')
                  ->onDelete('cascade');
            $table->string('service_type');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('request_services');
    }
};