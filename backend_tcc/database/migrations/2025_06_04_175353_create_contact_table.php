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
        Schema::create('contact', function (Blueprint $table) {
            $table->id();
            $table->foreignId('receiver_id')->references('id')->on('users')->onDelete('cascade'); //quem envia
            $table->foreignId('sender_id')->references('id')->on('users')->onDelete('cascade'); //quem recebe
            $table->foreignId('animal_post_id')->references('id')->on('animal_posts')->onDelete('cascade');
            $table->text('message');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact');
    }
};
