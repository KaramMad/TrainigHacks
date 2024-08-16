<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Carbon\Carbon;

class DeleteOldPendingOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:delete-old-pending';

    protected $description = 'Delete orders that are pending for more than 1 hour';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $orders = Order::where('status', 'pending')
            ->where('created_at', '<=', Carbon::now()->subHour())
            ->get();

        foreach ($orders as $order) {
            $order->delete();
        }

        $this->info('Old pending orders deleted successfully.');
    }
}
