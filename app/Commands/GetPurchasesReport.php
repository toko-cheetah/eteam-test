<?php

namespace App\Commands;

use App\Helper;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Storage;
use LaravelZero\Framework\Commands\Command;

class GetPurchasesReport extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'get_purchases_report';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Generate a report of all purchases, including the product ID, product name, quantity, price and total expenses';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $productsData = Storage::json('products.json');
        $purchasesData = Storage::json('purchase-products.json');

        $orderedNames = array();
        $reportArr = array();

        if ($productsData && $purchasesData) {
            foreach ($purchasesData['id'] as $value) {
                $key = array_search($value, $productsData['id']);
                array_push($orderedNames, $productsData['name'][$key]);
            }

            $reportArr = array_slice($purchasesData, 0, 1) + array('name' => $orderedNames) + array_slice($purchasesData, 1);
        } else {
            return $this->error('Oops! You forgot to provide some required information.');
        }

        $rowsArr = (new Helper())->getRows($reportArr);

        $this->newLine();
        $this->info('Purchases report');
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
