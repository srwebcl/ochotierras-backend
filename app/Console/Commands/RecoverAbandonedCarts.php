<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RecoverAbandonedCarts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:recover-abandoned';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send recovery emails for abandoned carts';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $cutoffTime = now()->subHours(2);
        
        $abandonedOrders = \App\Models\Order::where('status', 'PENDING')
            ->where('abandoned_email_sent', false)
            ->where('created_at', '<=', $cutoffTime)
            ->whereNotNull('customer_email')
            ->get();

        foreach ($abandonedOrders as $order) {
            try {
                \Illuminate\Support\Facades\Mail::to($order->customer_email)
                    ->send(new \App\Mail\AbandonedCart($order));
                
                $order->update(['abandoned_email_sent' => true]);
                $this->info("Recovery email sent to {$order->customer_email}");
            } catch (\Exception $e) {
                $this->error("Failed to send email to {$order->customer_email}: {$e->getMessage()}");
            }
        }
    }
}
