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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Basic, Gold, Standard, Premium
            $table->decimal('price', 12, 2);
            $table->unsignedInteger('daily_task_limit')->default(5);
            $table->decimal('reward_per_task', 12, 2)->default(10.00);
            $table->decimal('referral_commission', 12, 2)->default(50.00);
            $table->unsignedInteger('duration_days')->default(30);
            $table->timestamps();
        });

        // 2. User Active Subscriptions
        Schema::create('user_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('package_id')->constrained()->cascadeOnDelete();
            $table->timestamp('started_at');
            $table->timestamp('expires_at');
            $table->enum('status', ['active', 'expired', 'upgraded'])->default('active');
            $table->timestamps();
        });

        // 3. Payment Proofs (Purchases & Upgrades)
        Schema::create('package_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('package_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->string('payment_method'); // JazzCash / Easypaisa
            $table->string('sender_number')->nullable();
            $table->string('trx_id')->nullable();
            $table->string('screenshot')->nullable();
            $table->enum('type', ['new', 'upgrade'])->default('new');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_note')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });

        // 4. Ledger Transactions
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->enum('type', ['task_reward', 'referral_bonus', 'withdraw', 'package_payment', 'refund']);
            $table->enum('balance_type', ['credit', 'debit']);
            $table->string('description');
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('package_payments');
        Schema::dropIfExists('user_packages');
        Schema::dropIfExists('packages');
    }
};
