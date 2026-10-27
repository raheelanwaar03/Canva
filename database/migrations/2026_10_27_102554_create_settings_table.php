<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Seed default settings so system works out of the box
        $defaults = [
            // Payment Gateway Details
            ['key' => 'payment_gateway_name', 'value' => 'EasyPaisa'],
            ['key' => 'payment_account_title', 'value' => 'Shama'],
            ['key' => 'payment_account_number', 'value' => '03714780429'],
            ['key' => 'payment_instruction', 'value' => 'After sending payment, upload receipt screenshot to proceed.'],

            // Ads & Canva Earnings Rate
            ['key' => 'default_ad_rate', 'value' => '25.00'],

            // Referral Commission %
            ['key' => 'referral_level_1_percent', 'value' => '10'],
            ['key' => 'referral_level_2_percent', 'value' => '5'],
            ['key' => 'referral_level_3_percent', 'value' => '2'],
        ];

        foreach ($defaults as $item) {
            DB::table('settings')->insert(array_merge($item, ['created_at' => now(), 'updated_at' => now()]));
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
