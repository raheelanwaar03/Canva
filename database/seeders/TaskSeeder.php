namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $ads = [
            ['title' => 'Canva Pro Brand Kit Promotion', 'description' => 'Explore corporate design branding templates on Canva.', 'ad_url' => 'https://canva.com', 'duration_seconds' => 15],
            ['title' => 'AI Magic Studio Sponsored Ad', 'description' => 'Discover generative AI design tools for marketing collateral.', 'ad_url' => 'https://canva.com', 'duration_seconds' => 15],
            ['title' => 'Social Media Video Ads Spotlight', 'description' => 'View short-form video template animations and trends.', 'ad_url' => 'https://canva.com', 'duration_seconds' => 15],
            ['title' => 'Print On Demand Commercial Feature', 'description' => 'Review custom business card and merchandise print services.', 'ad_url' => 'https://canva.com', 'duration_seconds' => 15],
            ['title' => 'Canva Enterprise Collaboration Ad', 'description' => 'Learn how modern global teams design together in real-time.', 'ad_url' => 'https://canva.com', 'duration_seconds' => 15],
        ];

        foreach ($ads as $ad) {
            Task::updateOrCreate(['title' => $ad['title']], $ad);
        }
    }
}
