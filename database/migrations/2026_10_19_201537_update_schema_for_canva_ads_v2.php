<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Update Wallets: Add rewards_balance (formerly Flowers Balance)
        Schema::table('wallets', function (Blueprint $table) {
            if (!Schema::hasColumn('wallets', 'rewards_balance')) {
                $table->decimal('rewards_balance', 12, 2)->default(0.00)->after('main_balance');
            }
        });

        // 2. Add first_task_bonus_received flag to users
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'first_task_bonus_received')) {
                $table->boolean('first_task_bonus_received')->default(false)->after('status');
            }
        });

        // 3. Balance Transfers Table
        if (!Schema::hasTable('transfers')) {
            Schema::create('transfers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('receiver_id')->constrained('users')->cascadeOnDelete();
                $table->decimal('amount', 12, 2);
                $table->decimal('fee', 12, 2);
                $table->decimal('total_deducted', 12, 2);
                $table->string('status')->default('completed');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('transfers');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('first_task_bonus_received');
        });
        Schema::table('wallets', function (Blueprint $table) {
            $table->dropColumn('rewards_balance');
        });
    }
};
