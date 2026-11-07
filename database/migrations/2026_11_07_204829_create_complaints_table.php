<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {$table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();$table->string('subject')->nullable();
            $table->text('message');$table->enum('status', ['pending', 'in_progress', 'resolved'])->default('pending');
            $table->text('admin_reply')->nullable();$table->foreignId('resolved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('resolved_at')->nullable();$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
