<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Storage;
use LaravelZero\Framework\Commands\Command;

class GetAveragePrice extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'get_average_price {product_id}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Display the average price of a specific product based on its purchase history. arguments: {product_id}';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $purchasesData = Storage::json('purchase-products.json');

        $totalExpense = 0;
        $totalQuantity = 0;

        if ($purchasesData) {
            for ($i=0; $i < count($purchasesData['id']); $i++) {
                if ($purchasesData['id'][$i] === $this->argument('product_id')) {
                    $totalQuantity += $purchasesData['quantity'][$i];
                    $totalExpense += $purchasesData['total_expense'][$i];
                }
            }
        }

        $averagePrice = $totalQuantity ? round($totalExpense / $totalQuantity, 2) : 0;

        $this->newLine();
        $this->info($averagePrice);
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
