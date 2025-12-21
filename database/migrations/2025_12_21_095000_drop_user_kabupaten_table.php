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
        Schema::dropIfExists('user_kabupaten');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('user_kabupaten', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_kabupaten')->constrained('kabupaten')->onDelete('cascade');
            $table->timestamps();
            
            // Ensure unique combination of user_id and id_kabupaten
            $table->unique(['user_id', 'id_kabupaten']);
        });
    }
};
