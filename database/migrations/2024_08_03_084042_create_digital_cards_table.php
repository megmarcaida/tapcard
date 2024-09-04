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
        Schema::create('digital_cards', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('digital_card')->unique();
            $table->string('profile_image')->nullable();
            $table->string('banner_image')->nullable();
            $table->text('title')->nullable();
            $table->text('sub_title')->nullable();
            $table->text('company')->nullable();
            $table->text('about_us')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('digital_cards');
    }
};
