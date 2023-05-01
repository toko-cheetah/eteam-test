<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Storage;
use LaravelZero\Framework\Commands\Command;

class GetFewestProduct extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'get_fewest_product';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Return the name of the product with the lowest remaining quantity';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $productsData = Storage::json('products.json');
        $purchasesData = Storage::json('purchase-products.json');
        $ordersData = Storage::json('order-products.json');

        $totalPurchases = array();
        $totalOrderes = array();
        $remainingQuantities = array();
        $lowestQuantityKey = null;
        $fewestProduct = null;

        if ($productsData && $purchasesData && $ordersData) {
            foreach ($productsData['id'] as $value) {
                for ($i=0; $i < count($purchasesData['id']); $i++) {
                    if ($purchasesData['id'][$i] === $value) {
                        $totalPurchases[$value] = array_key_exists($value, $totalPurchases)
                            ? $totalPurchases[$value] + $purchasesData['quantity'][$i]
                            : $purchasesData['quantity'][$i];
                    }
                }

                for ($i=0; $i < count($ordersData['id']); $i++) {
                    if ($ordersData['id'][$i] === $value) {
                        $totalOrderes[$value] = array_key_exists($value, $totalOrderes)
                            ? $totalOrderes[$value] + $ordersData['quantity'][$i]
                            : $ordersData['quantity'][$i];
                    }
                }

                array_push($remainingQuantities, $totalPurchases[$value] - $totalOrderes[$value]);
            }

            $lowestQuantityKey = array_search(min($remainingQuantities), $remainingQuantities);
            $fewestProduct = $productsData['name'][$lowestQuantityKey];
        } else {
            return $this->error('Oops! You forgot to provide some required information.');
        }

        $this->newLine();
        $this->info($fewestProduct . ' - ' . min($remainingQuantities));
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
