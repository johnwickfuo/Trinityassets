<?php

namespace App\Mail;

use App\Models\Nft;
use App\Models\Settings;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NftActivityMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $nft;
    public $activity;
    public $amount;
    public $nftBalance;
    public $actionUrl;
    public $siteName;

    public function __construct(User $user, Nft $nft, string $activity, $amount, $nftBalance = null)
    {
        $this->user = $user;
        $this->nft = $nft;
        $this->activity = $activity;
        $this->amount = $amount;
        $this->nftBalance = $nftBalance;
        $this->siteName = optional(Settings::find(1))->site_name ?: config('app.name');
        $this->actionUrl = $activity === 'sold'
            ? route('user.nfts.collection')
            : route('user.nfts.show', $nft);
    }

    public function build()
    {
        $subjects = [
            'purchased' => 'Your NFT purchase is confirmed',
            'bid' => 'You received a new NFT bid',
            'sold' => 'Your NFT sale is confirmed',
        ];

        return $this->markdown('emails.nft-activity')
            ->subject($subjects[$this->activity] ?? 'NFT account update');
    }
}
