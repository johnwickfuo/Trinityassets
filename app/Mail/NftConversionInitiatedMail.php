<?php

namespace App\Mail;

use App\Models\NftConversion;
use App\Models\Settings;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NftConversionInitiatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $conversion;
    public $siteName;
    public $detailsUrl;

    public function __construct(User $user, NftConversion $conversion)
    {
        $this->user = $user;
        $this->conversion = $conversion;
        $this->siteName = optional(Settings::find(1))->site_name ?: config('app.name');
        $this->detailsUrl = route('user.nfts.conversions.show', $conversion);
    }

    public function build()
    {
        return $this->markdown('emails.nft-conversion-initiated')
            ->subject('NFT conversion pending – '.$this->conversion->reference);
    }
}
