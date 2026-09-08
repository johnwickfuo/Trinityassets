@component('mail::message')
# Hello {{ $user->name }},

@if ($activity === 'purchased')
Your purchase of **{{ $nft->name }}** has been confirmed and the NFT is now in your collection.

**Purchase price:** {{ $nft->currency }} {{ number_format($amount, 2) }}  
**Projected value:** {{ $nft->currency }} {{ number_format($nft->projected_min_value, 2) }} – {{ number_format($nft->projected_max_value, 2) }}

@component('mail::button', ['url' => $actionUrl])
View My NFT
@endcomponent
@elseif ($activity === 'bid')
Your NFT **{{ $nft->name }}** has received a new bid.

**Current bid:** {{ $nft->currency }} {{ number_format($amount, 2) }}

Open the NFT from your collection to review the bid and decide whether to sell.

@component('mail::button', ['url' => $actionUrl])
Review Bid
@endcomponent
@elseif ($activity === 'sold')
Your sale of **{{ $nft->name }}** has been completed successfully.

**Sale price:** {{ $nft->currency }} {{ number_format($amount, 2) }}  
**NFT balance:** {{ $nft->currency }} {{ number_format($nftBalance, 2) }}

The sale amount has been credited to your NFT balance.

@component('mail::button', ['url' => $actionUrl])
View My NFTs
@endcomponent
@endif

Regards,  
The {{ $siteName }} Team
@endcomponent
