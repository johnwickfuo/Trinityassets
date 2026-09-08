@extends('layouts.dasht')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="mb-8"><p class="text-sm font-semibold text-blue-600 dark:text-blue-400">NFT BALANCE</p><h1 class="text-3xl font-bold text-gray-900 dark:text-white">Swap NFT for Cash</h1><p class="mt-2 text-gray-600 dark:text-gray-400">Convert NFT/USDT balance to your main TTD balance at 10 TTD per 1 USDT.</p></div>
        @if($errors->any())<div class="mb-6 rounded-xl bg-red-50 p-4 text-red-700 dark:bg-red-900/20 dark:text-red-300">{{ $errors->first() }}</div>@endif
        <div class="grid gap-6 lg:grid-cols-5">
            <div class="lg:col-span-2 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                <p class="text-sm text-gray-500 dark:text-gray-400">Available NFT balance</p><p class="mt-1 text-3xl font-bold text-gray-900 dark:text-white">USDT {{ number_format(Auth::user()->nft_balance, 2) }}</p>
                <form method="POST" action="{{ route('user.nfts.conversions.store') }}" class="mt-6" x-data="{ amount: '' }">@csrf
                    <label for="conversion-amount" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Amount to convert</label>
                    <input id="conversion-amount" name="amount" x-model.number="amount" type="number" min="1" max="{{ Auth::user()->nft_balance }}" step="0.01" required class="mt-2 w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-gray-900 dark:border-gray-600 dark:bg-gray-900 dark:text-white" placeholder="0.00">
                    <div class="mt-4 space-y-2 rounded-xl bg-gray-50 p-4 text-sm dark:bg-gray-900"><div class="flex justify-between text-gray-600 dark:text-gray-300"><span>You receive</span><strong x-text="'TTD ' + ((Number(amount) || 0) * 10).toFixed(2)">TTD 0.00</strong></div><div class="flex justify-between text-gray-600 dark:text-gray-300"><span>10% fee</span><strong x-text="'TTD ' + ((Number(amount) || 0)).toFixed(2)">TTD 0.00</strong></div></div>
                    <button type="submit" class="mt-5 w-full rounded-xl bg-blue-600 px-4 py-3 font-semibold text-white hover:bg-blue-700">Start Conversion</button>
                </form>
            </div>
            <div class="lg:col-span-3">
                <h2 class="mb-4 text-xl font-semibold text-gray-900 dark:text-white">Conversion History</h2>
                <div class="space-y-3">
                    @forelse($conversions as $conversion)
                        <a href="{{ route('user.nfts.conversions.show', $conversion) }}" class="block rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200 transition hover:ring-blue-500 dark:bg-gray-800 dark:ring-gray-700">
                            <div class="flex flex-wrap items-start justify-between gap-3"><div><p class="font-semibold text-gray-900 dark:text-white">{{ $conversion->reference }}</p><p class="mt-1 text-sm text-gray-500">USDT {{ number_format($conversion->nft_amount, 2) }} → TTD {{ number_format($conversion->converted_amount, 2) }}</p></div><span class="rounded-full px-3 py-1 text-xs font-semibold capitalize {{ $conversion->status === 'approved' ? 'bg-green-100 text-green-700' : ($conversion->status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">{{ $conversion->status }}</span></div>
                            @if($conversion->status === 'pending')<p class="mt-3 text-sm font-medium text-orange-600" data-countdown="{{ $conversion->expires_at->toIso8601String() }}">Calculating time remaining…</p>@endif
                        </a>
                    @empty<div class="rounded-2xl border border-dashed border-gray-300 p-10 text-center text-gray-500 dark:border-gray-700">No conversions yet.</div>@endforelse
                </div>
                <div class="mt-6">{{ $conversions->links('pagination::tailwind') }}</div>
            </div>
        </div>
    </div>
</div>
<script>
function updateConversionCountdowns(){document.querySelectorAll('[data-countdown]').forEach(function(el){const remaining=new Date(el.dataset.countdown).getTime()-Date.now();if(remaining<=0){el.textContent='Deadline reached';return;}const h=Math.floor(remaining/3600000),m=Math.floor((remaining%3600000)/60000),s=Math.floor((remaining%60000)/1000);el.textContent=`Time remaining: ${h}h ${m}m ${s}s`;});}
updateConversionCountdowns();setInterval(updateConversionCountdowns,1000);
</script>
@endsection
