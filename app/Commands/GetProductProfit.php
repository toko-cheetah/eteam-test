<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Storage;
use LaravelZero\Framework\Commands\Command;

class GetProductProfit extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'get_product_profit {product_id}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Display the profit earned from a specific product by comparing the average purchase price with the average order price. arguments: {product_id}';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $purchasesData = Storage::json('purchase-products.json');
        $ordersData = Storage::json('order-products.json');

        $totalPurchasedQuantity = 0;
        $totalExpense = 0;

        $totalOrderedQuantity = 0;
        $totalIncome = 0;

        if ($purchasesData && $ordersData) {
            for ($i=0; $i < count($purchasesData['id']); $i++) {
                if ($purchasesData['id'][$i] === $this->argument('product_id')) {
                    $totalPurchasedQuantity += $purchasesData['quantity'][$i];
                    $totalExpense += $purchasesData['total_expense'][$i];
                }
            }

            for ($i=0; $i < count($ordersData['id']); $i++) {
                if ($ordersData['id'][$i] === $this->argument('product_id')) {
                    $totalOrderedQuantity += $ordersData['quantity'][$i];
                    $totalIncome += $ordersData['total_income'][$i];
                }
            }
        }

        $averagePurchasePrice = $totalPurchasedQuantity ? $totalExpense / $totalPurchasedQuantity : 0;
        $averageOrderPrice = $totalOrderedQuantity ? $totalIncome / $totalOrderedQuantity : 0;

        $profitPerUnit = $averageOrderPrice - $averagePurchasePrice;
        $totalProfit = $profitPerUnit * $totalOrderedQuantity;

        $this->newLine();
        $this->info(round($totalProfit, 2));
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
