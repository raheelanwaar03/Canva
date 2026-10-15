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
        Schema::create('channels', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // e.g. "Canva Official", "Canva Extra Bonus & Rewards"
            $table->string('url');   // e.g. https://t.me/..., https://chat.whatsapp.com/...
            $table->string('icon')->default('fa-brands fa-telegram'); // Icon class or platform
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('channels');
    }
};
