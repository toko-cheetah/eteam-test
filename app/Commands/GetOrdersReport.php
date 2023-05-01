<?php

namespace App\Commands;

use App\Helper;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Storage;
use LaravelZero\Framework\Commands\Command;

class GetOrdersReport extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'get_orders_report';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Generate a report of all orders, including the product ID, product name, quantity, price and cost of goods sold (COGS)';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $productsData = Storage::json('products.json');
        $ordersData = Storage::json('order-products.json');

        $orderedNames = array();
        $reportArr = array();

        if ($productsData && $ordersData) {
            foreach ($ordersData['id'] as $value) {
                $key = array_search($value, $productsData['id']);
                array_push($orderedNames, $productsData['name'][$key]);
            }

            $reportArr = array_slice($ordersData, 0, 1) + array('name' => $orderedNames) + array_slice($ordersData, 1);
        } else {
            return $this->error('Oops! You forgot to provide some required information.');
        }

        $rowsArr = (new Helper())->getRows($reportArr);

        $this->newLine();
        $this->info('Orders report');
        $this->newLine();
        $this->table(array_keys($reportArr), $rowsArr);
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
