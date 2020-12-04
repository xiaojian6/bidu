<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use App\Events\BlockChainTxEvent;
use App\Jobs\ChainBlockInsert;
class BlockChainTxListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(BlockChainTxEvent $event)
    {
        $tx = $event->tx;
        Log::channel('blockchain')->critical('新的链上交易:', $tx);
        ChainBlockInsert::dispatch($tx)->onQueue('charge:insert');
    }
}
