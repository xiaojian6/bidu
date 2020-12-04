<?php

namespace App\Console;

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
        Commands\AutoCancelLegal::class,
        Commands\UpdateBalance::class,
        Commands\ClearMarketVolume::class,
        Commands\UpdateHashStatus::class,
        Commands\ReturnTransTate::class,
        Commands\Airdrop::class,
        Commands\AirdropCash::class,
        Commands\LockRelease::class,
        Commands\StartAirdrop::class,
        Commands\UnlockCurrentDeposit::class,
        Commands\RebackFixedDeposit::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('update_hash_status')->everyFiveMinutes()->withoutOverlapping(); //更新哈希值状态
        $schedule->command('market:clear:volume')->withoutOverlapping()->dailyAt('00:00'); //清空24小时成交量
        $schedule->command('lever:overnight')->dailyAt('00:01'); //收取隔夜费
        $schedule->command('auto_cancel_legal')->hourly()->appendOutputTo('./auto_cancel_legal.log');

        $schedule->command('return:transrete')->withoutOverlapping()->runInBackground()->dailyAt('00:00');

        $schedule->command('airdrop')->dailyAt('22:01');
        $schedule->command('airdrop_cash')->dailyAt('22:30');
        $schedule->command('locked_release')->dailyAt('00:01');
        $schedule->command('check_airdrop')->everyMinute();
        $schedule->command('unlock_current_deposit')->everyMinute();
        $schedule->command('reback_fixed_deposit')->everyMinute();
        $schedule->command('rechange_lock')->everyMinute();
        $schedule->command('rechange_release')->everyMinute();
        $schedule->command('gift_pse')->dailyAt('23:50');
            //->dailyAt('22:01');

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
