<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('withdrawals', function (Blueprint $table) {
            $table->string('username')->nullable()->after('method');
            $table->string('account_title')->nullable()->after('username');
            $table->string('account_number')->nullable()->after('account_title');
        });
    }

    public function down(): void
    {
        Schema::table('withdrawals', function (Blueprint $table) {
            $table->dropColumn(['username', 'account_title', 'account_number']);
        });
    }
};
