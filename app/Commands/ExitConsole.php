<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class ExitConsole extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'exit';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Close the console application';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->newLine();
        $this->info("Simply close the console and that's it,");
        $this->info("or type 'cd' and hit Enter.");
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
