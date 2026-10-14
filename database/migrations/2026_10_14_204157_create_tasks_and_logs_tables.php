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
        // 1. Available Ad Tasks pool
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('ad_url')->default('https://canva.com');
            $table->string('banner_image')->nullable();
            $table->unsignedInteger('duration_seconds')->default(15);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Task Completion Logs
        Schema::create('task_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('task_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_package_id')->constrained()->cascadeOnDelete();
            $table->decimal('reward_earned', 12, 2);
            $table->date('completed_date'); // To easily check daily limits
            $table->timestamps();

            // Prevent completing the same task twice on the same day
            $table->unique(['user_id', 'task_id', 'completed_date'], 'unique_user_task_day');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_logs');
        Schema::dropIfExists('tasks');
    }
};
