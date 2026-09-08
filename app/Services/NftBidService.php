<?php

namespace App\Services;

use App\Models\Nft;
use App\Models\NftBid;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class NftBidService
{
    public function createAutomatic(Nft $nft): NftBid
    {
        return DB::transaction(function () use ($nft) {
            $lockedNft = Nft::whereKey($nft->id)->lockForUpdate()->firstOrFail();
            $this->ensureOwned($lockedNft);

            $minimum = $this->toCents($lockedNft->projected_min_value);
            $maximum = $this->toCents($lockedNft->projected_max_value);
            if ($minimum <= 0 || $maximum < $minimum) {
                throw ValidationException::withMessages(['nft' => 'Set a valid projected value range first.']);
            }

            return $this->replaceCurrentBid($lockedNft, random_int($minimum, $maximum) / 100, 'automatic');
        });
    }

    public function createManual(Nft $nft, $amount): NftBid
    {
        return DB::transaction(function () use ($nft, $amount) {
            $lockedNft = Nft::whereKey($nft->id)->lockForUpdate()->firstOrFail();
            $this->ensureOwned($lockedNft);

            return $this->replaceCurrentBid($lockedNft, $amount, 'manual');
        });
    }

    private function replaceCurrentBid(Nft $nft, $amount, string $source): NftBid
    {
        NftBid::where('nft_id', $nft->id)->where('status', 'active')->update(['status' => 'superseded']);

        return NftBid::create([
            'nft_id' => $nft->id,
            'amount' => number_format((float) $amount, 2, '.', ''),
            'currency' => $nft->currency,
            'source' => $source,
            'status' => 'active',
        ]);
    }

    private function toCents($amount): int
    {
        return (int) round(((float) $amount) * 100);
    }

    private function ensureOwned(Nft $nft): void
    {
        if (!$nft->owner_user_id) {
            throw ValidationException::withMessages(['nft' => 'Only a purchased NFT can receive a bid.']);
        }
    }
}
