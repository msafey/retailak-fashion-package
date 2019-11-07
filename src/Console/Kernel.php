<?php

namespace App\Console;

use App\Console\Commands\AddParentSlugToBrands;
use App\Console\Commands\AddParentSlugToCategories;
use App\Console\Commands\AddParentSlugToProducts;
use App\Console\Commands\AddPermissions;
use App\Console\Commands\InsertSlugToBrands;
use App\Console\Commands\InsertSlugToCategories;
use App\Console\Commands\InsertSlugToProducts;
use App\Console\Commands\UpdateOrders;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        AddPermissions::class,
        AddParentSlugToProducts::class,
        AddParentSlugToCategories::class,
        AddParentSlugToBrands::class,
        InsertSlugToProducts::class,
        InsertSlugToCategories::class,
        InsertSlugToBrands::class,
        UpdateOrders::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
