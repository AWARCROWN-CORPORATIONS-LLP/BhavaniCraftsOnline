<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\CartItem;
use App\Mail\AbandonedCartReminder;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendAbandonedCartReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-abandoned-cart-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email reminders to users with items in their cart for over 24 hours.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Settings: Send reminder if cart was last updated between 24 and 26 hours ago
        // This range ensures we only pick up people who crossed the 24h mark recently
        $startTime = Carbon::now()->subHours(26);
        $endTime = Carbon::now()->subHours(24);

        $this->info("Scanning for abandoned carts between {$startTime} and {$endTime}...");

        $items = CartItem::whereNotNull('user_id')
            ->whereBetween('updated_at', [$startTime, $endTime])
            ->get()
            ->groupBy('user_id');

        if ($items->isEmpty()) {
            $this->info("No abandoned carts found in this time range.");
            return;
        }

        foreach ($items as $userId => $cartItems) {
            $user = User::find($userId);
            if (!$user || !$user->email) continue;

            $this->info("Sending reminder to: {$user->email}");

            try {
                Mail::to($user->email)->queue(new AbandonedCartReminder($user, $cartItems));
            } catch (\Exception $e) {
                $this->error("Failed to send to {$user->email}: " . $e->getMessage());
            }
        }

        $this->info("Abandoned cart scan complete.");
    }
}
