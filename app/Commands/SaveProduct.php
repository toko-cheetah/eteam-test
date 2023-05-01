<?php

namespace App\Commands;

use App\Helper;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Storage;
use LaravelZero\Framework\Commands\Command;

class SaveProduct extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'save_product {product_id} {product_name} {price}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Add a new product to the catalog or modify an existing one. arguments: {product_id} {product_name} {price}';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (!is_numeric($this->argument('price'))) {
            return $this->error('The price must be numeric!');
        }

        $values = array(
            'id' => array($this->argument('product_id')),
            'name' => array($this->argument('product_name')),
            'price' => array($this->argument('price')),
        );

        if (Storage::exists('products.json')) {
            $storageData = Storage::json('products.json');
            $idKey = array_search($this->argument('product_id'), $storageData['id']);

            if (in_array($this->argument('product_id'), $storageData['id'])) {
                array_splice($storageData['id'], $idKey, 1, $this->argument('product_id'));
                array_splice($storageData['name'], $idKey, 1, $this->argument('product_name'));
                array_splice($storageData['price'], $idKey, 1, $this->argument('price'));
            } else {
                array_push($storageData['id'], $this->argument('product_id'));
                array_push($storageData['name'], $this->argument('product_name'));
                array_push($storageData['price'], $this->argument('price'));
            }

            $values['id'] = $storageData['id'];
            $values['name'] = $storageData['name'];
            $values['price'] = $storageData['price'];
        }

        Storage::put('products.json', json_encode($values));

        $result = Storage::json('products.json');

        $rowsArr = (new Helper())->getRows($result);

        $this->newLine();
        $this->info('Product added!');
        $this->newLine();
        $this->table(array_keys($result), $rowsArr);
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
