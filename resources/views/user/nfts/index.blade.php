@extends('layouts.dasht')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="border-b border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-10">
            <p class="text-sm font-semibold text-blue-600 dark:text-blue-400 mb-2">DIGITAL ART</p>
            <h1 class="text-3xl font-semibold text-gray-900 dark:text-white">Buy NFT</h1>
            <p class="mt-3 text-gray-600 dark:text-gray-400">Explore available artwork and discover your next NFT.</p>
        </div>
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
        <div class="flex items-center justify-between gap-4 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Available NFTs</h2>
            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $nfts->total() }} {{ $nfts->total() === 1 ? 'artwork' : 'artworks' }}</span>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse ($nfts as $nft)
                <article class="overflow-hidden rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm">
                    <img src="{{ route('user.nfts.image', $nft) }}" alt="{{ $nft->name }}" loading="lazy" class="w-full h-64 object-cover bg-gray-100 dark:bg-gray-700">
                    <div class="p-5">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white break-words">{{ $nft->name }}</h3>
                        @if ($nft->description)
                            <p class="mt-3 text-sm leading-relaxed text-gray-600 dark:text-gray-400 break-words whitespace-pre-line">{{ $nft->description }}</p>
                        @endif
                        <div class="mt-5 pt-4 border-t border-gray-100 dark:border-gray-700">
                            <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Price</p>
                            <p class="mt-1 text-2xl font-semibold text-blue-600 dark:text-blue-400">{{ $nft->currency }} {{ number_format($nft->price, 2) }}</p>
                        </div>
                    </div>
                </article>
            @empty
                <div class="sm:col-span-2 xl:col-span-3 rounded-2xl border border-dashed border-gray-300 dark:border-gray-700 px-6 py-16 text-center">
                    <i data-lucide="image" class="w-12 h-12 mx-auto text-gray-400 mb-4"></i>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">New artwork is on its way</h3>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">There are no NFTs available yet. Check back to discover new additions.</p>
                </div>
            @endforelse
        </div>
        <div class="mt-8">{{ $nfts->links('pagination::tailwind') }}</div>
    </div>
</div>
@endsection
