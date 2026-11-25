<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // New users registering after this migration automatically get true
            $table->boolean('joining_bonus_eligible')->default(true)->after('status');
            $table->boolean('joining_bonus_claimed')->default(false)->after('joining_bonus_eligible');
        });

        // Mark all existing users as ineligible so only new registrations receive the bonus
        DB::table('users')->update([
            'joining_bonus_eligible' => false,
            'joining_bonus_claimed'  => false,
        ]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['joining_bonus_eligible', 'joining_bonus_claimed']);
        });
    }
};
