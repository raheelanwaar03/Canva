<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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
    protected $signature = 'app:clean {--force : Force execution in production}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fresh migrate database, purge all uploaded storage images, recreate symlink, clear caches, and seed fresh records';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Production safety confirmation
        if (app()->environment('production') && !$this->option('force')) {
            if (!$this->confirm('WARNING: You are in PRODUCTION. This will wipe all user data, wallets, and uploaded images. Continue?')) {
                $this->info('Clean operation aborted.');
                return Command::SUCCESS;
            }
        }

        // 1. Wipe and recreate physical storage image directories
        $this->warn('Purging storage image directories (payments, avatars)...');

        // Remove uploaded files
        Storage::disk('public')->deleteDirectory('payments');
        Storage::disk('public')->deleteDirectory('avatars');

        // Recreate clean empty directories
        Storage::disk('public')->makeDirectory('payments');
        Storage::disk('public')->makeDirectory('avatars');

        // 2. Refresh Storage Symlink
        $this->info('Resetting public/storage symlink...');
        $publicStoragePath = public_path('storage');
        if (File::exists($publicStoragePath)) {
            File::delete($publicStoragePath);
        }
        Artisan::call('storage:link');

        // 3. Database Fresh Migration
        $this->warn('Resetting database and running fresh migrations...');
        Artisan::call('migrate:fresh', ['--force' => true]);

        // 4. Clear all compiled views, routes, configs, and application cache
        $this->info('Clearing views, cache, config, and route caches...');
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');

        // 5. Create Default Admin Account
        $this->info('Creating Default Admin...');
        $admin = User::create([
            'name' => 'Canva ADS Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('asdfasdf'),
            'referral_code' => 'ADMIN01',
            'referred_by' => null,
            'role' => 'admin',
            'status' => 'active',
            'first_task_bonus_received' => true,
        ]);

        $admin->wallet()->create([
            'main_balance' => 0.00,
            'rewards_balance' => 0.00,
            'task_earnings' => 0.00,
            'referral_earnings' => 0.00,
            'staff_earnings' => 0.00,
            'withdrawable_balance' => 0.00,
        ]);

        // 6. Create Default Test Member
        $this->info('Creating Default Test User...');
        $testUser = User::create([
            'name' => 'Khadija Zaman',
            'email' => 'user@gmail.com',
            'password' => Hash::make('asdfasdf'),
            'referral_code' => 'USER777',
            'referred_by' => $admin->id,
            'role' => 'user',
            'status' => 'active',
            'first_task_bonus_received' => false,
        ]);

        $testUser->wallet()->create([
            'main_balance' => 1500.00,
            'rewards_balance' => 600.00,
            'task_earnings' => 200.00,
            'referral_earnings' => 1300.00,
            'staff_earnings' => 0.00,
            'withdrawable_balance' => 1500.00,
        ]);

        // 7. Seed the 4 Packages with Client V2 Limits & Commissions
        $this->info('Seeding the 4 Canva ADS Packages...');
        $packages = [
            [
                'name' => 'Basic',
                'price' => 500.00,
                'daily_task_limit' => 1,
                'reward_per_task' => 7.00,
                'referral_commission' => 125.00,
                'duration_days' => 30,
            ],
            [
                'name' => 'Gold',
                'price' => 1500.00,
                'daily_task_limit' => 2,
                'reward_per_task' => 7.00,
                'referral_commission' => 375.00,
                'duration_days' => 30,
            ],
            [
                'name' => 'Standard',
                'price' => 2500.00,
                'daily_task_limit' => 4,
                'reward_per_task' => 7.00,
                'referral_commission' => 625.00,
                'duration_days' => 30,
            ],
            [
                'name' => 'Premium',
                'price' => 5500.00,
                'daily_task_limit' => 6,
                'reward_per_task' => 7.00,
                'referral_commission' => 1375.00,
                'duration_days' => 30,
            ],
        ];

        foreach ($packages as $pkg) {
            Package::create($pkg);
        }

        // 8. Seed Default Advertising Tasks
        $this->info('Seeding default advertising tasks...');
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

        // 9. Seed Official Community Channels
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
        $this->info('All old images wiped. Database, storage symlink, and fresh seed data initialized!');
        $this->table(
            ['Role', 'Email', 'Password', 'Referral Code'],
            [
                ['Admin', 'admin@gmail.com', 'asdfasdf', 'ADMIN01'],
                ['User', 'user@gmail.com', 'asdfasdf', 'USER777'],
            ]
        );

        return Command::SUCCESS;
    }
}
