<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Storage;
use LaravelZero\Framework\Commands\Command;

class GetMostPopularProduct extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'get_most_popular_product';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Return the name of the product with the highest number of orders';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $productsData = Storage::json('products.json');
        $ordersData = Storage::json('order-products.json');

        $totalOrderes = array();
        $highestQuantityKey = null;
        $popularProduct = null;

        if ($productsData && $ordersData) {
            foreach ($productsData['id'] as $value) {
                for ($i=0; $i < count($ordersData['id']); $i++) {
                    if ($ordersData['id'][$i] === $value) {
                        $totalOrderes[$value] = array_key_exists($value, $totalOrderes)
                            ? $totalOrderes[$value] + $ordersData['quantity'][$i]
                            : $ordersData['quantity'][$i];
                    }
                }
            }

            $highestQuantityKey = array_search(max($totalOrderes), array_values($totalOrderes));
            $popularProduct = $productsData['name'][$highestQuantityKey];
        } else {
            return $this->error('Oops! You forgot to provide some required information.');
        }

        $this->newLine();
        $this->info($popularProduct . ' - ' . max($totalOrderes));
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
