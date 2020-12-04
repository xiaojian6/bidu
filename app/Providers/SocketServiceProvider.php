<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use GatewayWorker\Lib\Gateway;

class SocketServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Gateway::$registerAddress = config('socket.register_address', '127.0.0.1:1236');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
