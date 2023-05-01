<?php

namespace App\Commands;

use App\Helper;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Storage;
use LaravelZero\Framework\Commands\Command;

class PurchaseProduct extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'purchase_product {product_id} {quantity} {price}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Purchase a product, increasing its balance based on the specified quantity. arguments: {product_id} {quantity} {price}';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (!is_numeric($this->argument('quantity')) || !is_numeric($this->argument('price'))) {
            return $this->error('The quantity and price must be numeric!');
        }

        if ($this->confirm('Do you wish to purchase a product?')) {
            $totalExpense = $this->argument('quantity') * $this->argument('price');

            $values = array(
                'id' => array($this->argument('product_id')),
                'quantity' => array($this->argument('quantity')),
                'price' => array($this->argument('price')),
                'total_expense' => array($totalExpense),
            );

            if (Storage::exists('purchase-products.json')) {
                $storageData = Storage::json('purchase-products.json');

                array_push($storageData['id'], $this->argument('product_id'));
                array_push($storageData['quantity'], $this->argument('quantity'));
                array_push($storageData['price'], $this->argument('price'));
                array_push($storageData['total_expense'], $totalExpense);


                $values['id'] = $storageData['id'];
                $values['quantity'] = $storageData['quantity'];
                $values['price'] = $storageData['price'];
                $values['total_expense'] = $storageData['total_expense'];
            }

            Storage::put('purchase-products.json', json_encode($values));

            $result = Storage::json('purchase-products.json');

            $rowsArr = (new Helper())->getRows($result);

            $this->info('Product purchased!');
            $this->newLine();
            $this->table(array_keys($result), $rowsArr);
            $this->newLine();
        }
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
