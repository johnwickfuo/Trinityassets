<?php

namespace App\Console\Commands;

use App\Models\Nft;
use App\Services\NftBidService;
use Illuminate\Console\Command;

class GenerateNftBids extends Command
{
    protected $signature = 'nfts:generate-bids';
    protected $description = 'Generate daily bids for owned NFTs with automatic bidding enabled';

    public function handle(NftBidService $bidService)
    {
        $generated = 0;
        Nft::whereNotNull('owner_user_id')->where('auto_bid_enabled', true)
            ->orderBy('id')->chunkById(100, function ($nfts) use ($bidService, &$generated) {
                foreach ($nfts as $nft) {
                    $bidService->createAutomatic($nft);
                    $generated++;
                }
            });

        $this->info("Generated {$generated} NFT bids.");
        return 0;
    }
}
