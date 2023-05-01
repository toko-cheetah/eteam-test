<?php

namespace App\Commands;

use App\Helper;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Storage;
use LaravelZero\Framework\Commands\Command;

class OrderProduct extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'order_product {product_id} {quantity}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Place an order for the product, decreasing its balance according to the specified quantity. arguments: {product_id} {quantity}';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (!is_numeric($this->argument('quantity'))) {
            return $this->error('The quantity must be numeric!');
        }

        if ($this->confirm('Do you wish to place an order?')) {
            $orderPrice = null;

            if (Storage::exists('products.json')) {
                $productsData = Storage::json('products.json');
                $idKey = array_search($this->argument('product_id'), $productsData['id']);

                if (in_array($this->argument('product_id'), $productsData['id'])) {
                    $orderPrice = $productsData['price'][$idKey];
                } else {
                    return $this->error('Add a Product to the catalog first!');
                }
            } else {
                return $this->error('Products catalog is empty!');
            }

            $totalIncome = $this->argument('quantity') * $orderPrice;

            $values = array(
                'id' => array($this->argument('product_id')),
                'quantity' => array($this->argument('quantity')),
                'price' => array($orderPrice),
                'total_income' => array($totalIncome),
            );

            if (Storage::exists('order-products.json')) {
                $storageData = Storage::json('order-products.json');

                array_push($storageData['id'], $this->argument('product_id'));
                array_push($storageData['quantity'], $this->argument('quantity'));
                array_push($storageData['price'], $orderPrice);
                array_push($storageData['total_income'], $totalIncome);


                $values['id'] = $storageData['id'];
                $values['quantity'] = $storageData['quantity'];
                $values['price'] = $storageData['price'];
                $values['total_income'] = $storageData['total_income'];
            }

            Storage::put('order-products.json', json_encode($values));

            $result = Storage::json('order-products.json');

            $rowsArr = (new Helper())->getRows($result);

            $this->info('An order has been placed!');
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
