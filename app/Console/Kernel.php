<?php

namespace App\Console;

use App\Models\ApiToken;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Artisan;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            // Используем chunkById, чтобы не уронить память
            ApiToken::query()->chunkById(100, function ($tokens) {
                foreach ($tokens as $token) {
                    Artisan::queue('api:import', [
                        '--api_token_id' => $token->id
                    ]);
                }
            });
        })->twiceDaily(1, 13);
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
