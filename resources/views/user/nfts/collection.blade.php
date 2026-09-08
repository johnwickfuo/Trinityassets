@extends('layouts.dasht')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        @if (session('success'))<div class="mb-6 rounded-xl bg-green-50 dark:bg-green-900/20 p-4 text-green-700 dark:text-green-300">{{ session('success') }}</div>@endif
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
            <div><p class="text-sm font-semibold text-blue-600 dark:text-blue-400">YOUR COLLECTION</p><h1 class="text-3xl font-semibold text-gray-900 dark:text-white">My NFTs</h1></div>
            <div class="rounded-xl bg-white dark:bg-gray-800 px-5 py-3 ring-1 ring-gray-200 dark:ring-gray-700">
                <p class="text-xs uppercase text-gray-500 dark:text-gray-400">NFT Balance</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ Auth::user()->currency }}{{ number_format(Auth::user()->nft_balance, 2) }}</p>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse($nfts as $nft)
                <a href="{{ route('user.nfts.show', $nft) }}" class="block overflow-hidden rounded-2xl bg-white dark:bg-gray-800 shadow-sm ring-1 ring-gray-200 dark:ring-gray-700 hover:ring-blue-500 transition">
                    <img src="{{ route('user.nfts.image', $nft) }}" alt="{{ $nft->name }}" class="w-full h-64 object-cover">
                    <div class="p-5"><h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $nft->name }}</h2><p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Purchased for {{ $nft->currency }} {{ number_format($nft->purchase_price, 2) }}</p>
                        <p class="mt-4 font-semibold {{ $nft->currentBid ? 'text-green-600 dark:text-green-400' : 'text-gray-500' }}">{{ $nft->currentBid ? 'Current bid: '.$nft->currency.' '.number_format($nft->currentBid->amount, 2) : 'No active bid yet' }}</p>
                    </div>
                </a>
            @empty
                <div class="sm:col-span-2 xl:col-span-3 rounded-2xl border border-dashed border-gray-300 dark:border-gray-700 p-12 text-center"><h2 class="text-xl font-semibold text-gray-900 dark:text-white">Your collection is empty</h2><a href="{{ route('user.nfts.index') }}" class="inline-block mt-4 text-blue-600 dark:text-blue-400 font-semibold">Browse NFTs</a></div>
            @endforelse
        </div>
        <div class="mt-8">{{ $nfts->links('pagination::tailwind') }}</div>
    </div>
</div>
@endsection
