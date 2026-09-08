<?php

namespace App\Services;

use App\Mail\NftActivityMail;
use App\Models\Nft;
use App\Models\NftBid;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NftEmailService
{
    public function purchaseConfirmed(User $user, Nft $nft): void
    {
        $this->send($user, $nft, 'purchased', $nft->purchase_price);
    }

    public function bidReceived(User $user, Nft $nft, NftBid $bid): void
    {
        $this->send($user, $nft, 'bid', $bid->amount);
    }

    public function saleConfirmed(User $user, Nft $nft, NftBid $bid): void
    {
        $this->send($user, $nft, 'sold', $bid->amount, $user->nft_balance);
    }

    private function send(User $user, Nft $nft, string $activity, $amount, $nftBalance = null): void
    {
        try {
            Mail::to($user->email)->send(new NftActivityMail($user, $nft, $activity, $amount, $nftBalance));
        } catch (\Throwable $exception) {
            Log::error('Failed to send NFT activity email.', [
                'user_id' => $user->id,
                'nft_id' => $nft->id,
                'activity' => $activity,
                'error' => $exception->getMessage(),
            ]);
        }
    }
}
