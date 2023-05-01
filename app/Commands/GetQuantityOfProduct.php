<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Storage;
use LaravelZero\Framework\Commands\Command;

class GetQuantityOfProduct extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'get_quantity_of_product {product_id}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Return the remaining quantity of a specific product. arguments: {product_id}';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $purchasesData = Storage::json('purchase-products.json');
        $ordersData = Storage::json('order-products.json');

        $totalPurchased = 0;
        $totalOrdered = 0;

        if ($purchasesData && $ordersData) {
            for ($i=0; $i < count($purchasesData['id']); $i++) {
                if ($purchasesData['id'][$i] === $this->argument('product_id')) {
                    $totalPurchased += $purchasesData['quantity'][$i];
                }
            }

            for ($i=0; $i < count($ordersData['id']); $i++) {
                if ($ordersData['id'][$i] === $this->argument('product_id')) {
                    $totalOrdered += $ordersData['quantity'][$i];
                }
            }
        }

        $this->newLine();
        $this->info($totalPurchased - $totalOrdered);
        $this->newLine();
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
