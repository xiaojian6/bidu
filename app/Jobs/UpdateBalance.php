<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\DAO\BlockChainDAO;

class UpdateBalance implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $wallet;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($wallet)
    {
        $this->wallet = $wallet;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        BlockChainDAO::updateWalletBalance($this->wallet);
    }
}
