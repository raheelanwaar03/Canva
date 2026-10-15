<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Package;
use App\Models\Task;
use App\Models\Channel;

class CleanApp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fresh migrate database, clear all caches, and seed default Canva ADS setup';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->warn('Resetting database and running fresh migrations...');
        Artisan::call('migrate:fresh');

        $this->info('Clearing views, cache, config, and routes...');
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');

        // 1. Create Default Admin
        $this->info('Creating default Admin account...');
        $admin = User::create([
            'name' => 'Canva ADS Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('asdfasdf'),
            'referral_code' => 'ADMIN01',
            'referred_by' => null,
            'role' => 'admin',
            'status' => 'active',
        ]);

        $admin->wallet()->create([
            'main_balance' => 0.00,
            'task_earnings' => 0.00,
            'referral_earnings' => 0.00,
            'staff_earnings' => 0.00,
            'withdrawable_balance' => 0.00,
        ]);

        // 2. Create Default Test User
        $this->info('Creating default Test User...');
        $testUser = User::create([
            'name' => 'Khadija Zaman',
            'email' => 'user@gmail.com',
            'password' => Hash::make('asdfasdf'),
            'referral_code' => 'USER777',
            'referred_by' => $admin->id,
            'role' => 'user',
            'status' => 'active',
        ]);

        $testUser->wallet()->create([
            'main_balance' => 1500.00,
            'task_earnings' => 200.00,
            'referral_earnings' => 1300.00,
            'staff_earnings' => 0.00,
            'withdrawable_balance' => 1500.00,
        ]);

        // 3. Seed Exactly the 4 Required Packages
        $this->info('Seeding the 4 membership packages...');
        $packages = [
            [
                'name' => 'Basic',
                'price' => 500.00,
                'daily_task_limit' => 5,
                'reward_per_task' => 5.00,
                'referral_commission' => 50.00,
                'duration_days' => 30,
            ],
            [
                'name' => 'Gold',
                'price' => 1500.00,
                'daily_task_limit' => 10,
                'reward_per_task' => 8.00,
                'referral_commission' => 150.00,
                'duration_days' => 30,
            ],
            [
                'name' => 'Standard',
                'price' => 2500.00,
                'daily_task_limit' => 15,
                'reward_per_task' => 10.00,
                'referral_commission' => 250.00,
                'duration_days' => 30,
            ],
            [
                'name' => 'Premium',
                'price' => 5500.00,
                'daily_task_limit' => 25,
                'reward_per_task' => 12.00,
                'referral_commission' => 550.00,
                'duration_days' => 30,
            ],
        ];

        foreach ($packages as $pkg) {
            Package::create($pkg);
        }

        // 4. Seed Default Advertising Tasks
        $this->info('Seeding default Canva advertising tasks...');
        $ads = [
            ['title' => 'Canva Pro Brand Kit Promotion', 'description' => 'Explore corporate design branding templates on Canva.', 'ad_url' => 'https://canva.com', 'duration_seconds' => 15, 'is_active' => true],
            ['title' => 'AI Magic Studio Sponsored Ad', 'description' => 'Discover generative AI design tools for marketing collateral.', 'ad_url' => 'https://canva.com', 'duration_seconds' => 15, 'is_active' => true],
            ['title' => 'Social Media Video Ads Spotlight', 'description' => 'View short-form video template animations and trends.', 'ad_url' => 'https://canva.com', 'duration_seconds' => 15, 'is_active' => true],
            ['title' => 'Print On Demand Commercial Feature', 'description' => 'Review custom business card and merchandise print services.', 'ad_url' => 'https://canva.com', 'duration_seconds' => 15, 'is_active' => true],
            ['title' => 'Canva Enterprise Collaboration Ad', 'description' => 'Learn how modern global teams design together in real-time.', 'ad_url' => 'https://canva.com', 'duration_seconds' => 15, 'is_active' => true],
        ];

        foreach ($ads as $ad) {
            Task::create($ad);
        }

        // 5. Seed Official Community Channels
        $this->info('Seeding default official channels...');
        Channel::create([
            'title' => 'Canva Official',
            'url' => 'https://t.me/canva_official',
            'icon' => 'fa-brands fa-telegram',
            'is_active' => true,
        ]);

        Channel::create([
            'title' => 'Canva Extra Bonus & Rewards',
            'url' => 'https://chat.whatsapp.com/sample_invite',
            'icon' => 'fa-brands fa-whatsapp',
            'is_active' => true,
        ]);

        $this->newLine();
        $this->info('Canva ADS platform cleaned, migrated, and initialized successfully!');
        $this->table(
            ['Account', 'Email', 'Password', 'Referral Code'],
            [
                ['Admin', 'admin@gmail.com', 'asdfasdf', 'ADMIN01'],
                ['User', 'user@gmail.com', 'asdfasdf', 'USER777'],
            ]
        );

        return Command::SUCCESS;
    }
}
