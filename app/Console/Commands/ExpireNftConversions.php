<?php

namespace App\Console\Commands;

use App\Services\NftConversionService;
use Illuminate\Console\Command;

class ExpireNftConversions extends Command
{
    protected $signature = 'nfts:expire-conversions';
    protected $description = 'Reject expired NFT conversions and refund their reserved NFT balance';

    public function handle(NftConversionService $service)
    {
        $this->info('Expired '.$service->expireOverdue().' NFT conversion(s).');
        return 0;
    }
}
