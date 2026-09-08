@extends('layouts.dasht')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6">
        @if ($errors->any())<div class="mb-6 rounded-xl bg-red-50 dark:bg-red-900/20 p-4 text-red-700 dark:text-red-300">{{ $errors->first() }}</div>@endif
        <a href="{{ route('user.nfts.collection') }}" class="text-blue-600 dark:text-blue-400 font-semibold">← Back to My NFTs</a>
        <div class="mt-6 grid md:grid-cols-2 gap-8">
            <img src="{{ route('user.nfts.image', $nft) }}" alt="{{ $nft->name }}" class="w-full rounded-2xl object-cover shadow-sm">
            <div><h1 class="text-3xl font-semibold text-gray-900 dark:text-white">{{ $nft->name }}</h1>
                @if($nft->description)<p class="mt-4 text-gray-600 dark:text-gray-400 whitespace-pre-line">{{ $nft->description }}</p>@endif
                <dl class="mt-6 space-y-3 text-gray-700 dark:text-gray-300"><div class="flex justify-between"><dt>Purchase price</dt><dd class="font-semibold">{{ $nft->currency }} {{ number_format($nft->purchase_price, 2) }}</dd></div><div class="flex justify-between"><dt>Projected value</dt><dd class="font-semibold">{{ $nft->currency }} {{ number_format($nft->projected_min_value, 2) }} – {{ number_format($nft->projected_max_value, 2) }}</dd></div></dl>
                @if($nft->currentBid)
                    <div class="mt-8 rounded-2xl bg-green-50 dark:bg-green-900/20 p-5"><p class="text-sm text-green-700 dark:text-green-300">Current bid</p><p class="text-3xl font-bold text-green-700 dark:text-green-300">{{ $nft->currency }} {{ number_format($nft->currentBid->amount, 2) }}</p>
                        <form method="POST" action="{{ route('user.nfts.sell', $nft) }}" class="mt-4" onsubmit="return confirm('Accept this bid and sell your NFT?')">@csrf<input type="hidden" name="bid_id" value="{{ $nft->currentBid->id }}"><button type="submit" class="w-full rounded-xl bg-green-600 px-4 py-3 font-semibold text-white hover:bg-green-700">Sell NFT at current bid</button></form>
                    </div>
                @else<div class="mt-8 rounded-xl bg-gray-100 dark:bg-gray-800 p-5 text-gray-600 dark:text-gray-400">There is no active bid for this NFT yet.</div>@endif
            </div>
        </div>
        <div class="mt-10"><h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Bid history</h2><div class="overflow-hidden rounded-xl bg-white dark:bg-gray-800 ring-1 ring-gray-200 dark:ring-gray-700">
            @forelse($nft->bids as $bid)<div class="flex justify-between gap-4 border-b last:border-0 border-gray-100 dark:border-gray-700 p-4"><div><p class="font-semibold text-gray-900 dark:text-white">{{ $bid->currency }} {{ number_format($bid->amount, 2) }}</p><p class="text-xs text-gray-500">{{ ucfirst($bid->source) }} · {{ $bid->created_at->format('M d, Y h:i A') }}</p></div><span class="text-sm capitalize text-gray-600 dark:text-gray-300">{{ $bid->status }}</span></div>@empty<div class="p-6 text-gray-500">No bids yet.</div>@endforelse
        </div></div>
    </div>
</div>
@endsection
