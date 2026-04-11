<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CalculateProductA10Rankings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:calculate-product-a10-rankings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculates Phase 1 A10 search rankings (Velocity & Authority) for all products.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Starting Phase 1 A10 Ranking Calculations...");

        $products = DB::table('products')->pluck('id');
        $bar = $this->output->createProgressBar(count($products));
        $bar->start();

        foreach ($products as $productId) {
            // 1. Sales Velocity Score
            // Weight recent sales heavier
            $sales7 = DB::table('order_items')
                ->where('product_id', $productId)
                ->where('created_at', '>=', Carbon::now()->subDays(7))
                ->sum('quantity');

            $sales30 = DB::table('order_items')
                ->where('product_id', $productId)
                ->whereBetween('created_at', [Carbon::now()->subDays(30), Carbon::now()->subDays(7)])
                ->sum('quantity');

            $sales90 = DB::table('order_items')
                ->where('product_id', $productId)
                ->whereBetween('created_at', [Carbon::now()->subDays(90), Carbon::now()->subDays(30)])
                ->sum('quantity');

            $velocityScore = ($sales7 * 3) + ($sales30 * 1.5) + ($sales90 * 0.5);

            // 2. Authority Score (Ratings & Reviews)
            $avgRating = DB::table('product_reviews')
                ->where('product_id', $productId)
                ->avg('rating') ?: 0;
            
            $reviewCount = DB::table('product_reviews')
                ->where('product_id', $productId)
                ->count();
                
            // Authority is a mix of rating quality and volume
            $authorityScore = $avgRating * log10($reviewCount + 10); 

            // 3. Final A10 Score (Base)
            // Relevancy is handled dynamically in query, this calculates the static rank multiplier
            $finalScore = $velocityScore + $authorityScore;

            DB::table('products')->where('id', $productId)->update([
                'sales_velocity_score' => $velocityScore,
                'authority_score' => $authorityScore,
                'final_a10_score' => $finalScore,
            ]);

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("A10 Rankings successfully updated for all products!");
    }
}
