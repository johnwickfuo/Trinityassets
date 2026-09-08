@extends('layouts.dasht')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8"><div class="max-w-3xl mx-auto px-4 sm:px-6">
    <a href="{{ route('user.nfts.swap') }}" class="font-semibold text-blue-600 dark:text-blue-400">← Conversion History</a>
    @if(session('success'))<div class="mt-5 rounded-xl bg-green-50 p-4 text-green-700 dark:bg-green-900/20 dark:text-green-300">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="mt-5 rounded-xl bg-red-50 p-4 text-red-700 dark:bg-red-900/20 dark:text-red-300">{{ $errors->first() }}</div>@endif
    <div class="mt-6 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700 sm:p-8">
        <div class="flex items-start justify-between gap-4"><div><p class="text-sm text-gray-500">Conversion reference</p><h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $conversion->reference }}</h1></div><span class="rounded-full px-3 py-1 text-sm font-semibold capitalize {{ $conversion->status === 'approved' ? 'bg-green-100 text-green-700' : ($conversion->status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">{{ $conversion->status }}</span></div>
        <dl class="mt-8 divide-y divide-gray-100 dark:divide-gray-700">
            <div class="flex justify-between py-3"><dt class="text-gray-500">NFT amount reserved</dt><dd class="font-semibold text-gray-900 dark:text-white">{{ $conversion->from_currency }} {{ number_format($conversion->nft_amount, 2) }}</dd></div>
            <div class="flex justify-between py-3"><dt class="text-gray-500">Exchange rate</dt><dd class="font-semibold text-gray-900 dark:text-white">10 TTD = 1 USDT</dd></div>
            <div class="flex justify-between py-3"><dt class="text-gray-500">Amount to receive</dt><dd class="font-semibold text-gray-900 dark:text-white">{{ $conversion->to_currency }} {{ number_format($conversion->converted_amount, 2) }}</dd></div>
            <div class="flex justify-between py-3"><dt class="text-gray-500">Conversion fee</dt><dd class="font-semibold text-gray-900 dark:text-white">{{ $conversion->to_currency }} {{ number_format($conversion->fee_amount, 2) }}</dd></div>
            <div class="flex justify-between py-3"><dt class="text-gray-500">Fee status</dt><dd class="font-semibold capitalize text-gray-900 dark:text-white">{{ $conversion->fee_status }}</dd></div>
        </dl>
        @if($conversion->status === 'pending')
            <div class="mt-6 rounded-xl bg-orange-50 p-4 text-orange-700 dark:bg-orange-900/20 dark:text-orange-300"><p class="font-semibold" id="conversion-countdown" data-countdown="{{ $conversion->expires_at->toIso8601String() }}">Calculating time remaining…</p><p class="mt-1 text-sm">Pay the fee from your main balance before the deadline.</p></div>
            <form method="POST" action="{{ route('user.nfts.conversions.pay-fee', $conversion) }}" class="mt-5" onsubmit="return confirm('Pay the conversion fee from your main balance?')">@csrf<button type="submit" class="w-full rounded-xl bg-green-600 px-4 py-3 font-semibold text-white hover:bg-green-700">Pay {{ $conversion->to_currency }} {{ number_format($conversion->fee_amount, 2) }} Fee</button></form>
            <a href="{{ route('deposits') }}" class="mt-3 block text-center font-semibold text-blue-600 dark:text-blue-400">Deposit to Main Balance</a>
        @elseif($conversion->status === 'rejected')
            <div class="mt-6 rounded-xl bg-red-50 p-4 text-red-700 dark:bg-red-900/20 dark:text-red-300"><strong>Reason:</strong> {{ $conversion->rejection_reason ?: 'Conversion rejected.' }} The reserved NFT amount was returned to your NFT balance.</div>
        @else<div class="mt-6 rounded-xl bg-green-50 p-4 text-green-700 dark:bg-green-900/20 dark:text-green-300">The converted amount has been credited to your main balance.</div>@endif
    </div>
</div></div>
@if($conversion->status === 'pending')<script>function tick(){const e=document.getElementById('conversion-countdown'),r=new Date(e.dataset.countdown).getTime()-Date.now();if(r<=0){e.textContent='Deadline reached';return;}const h=Math.floor(r/3600000),m=Math.floor((r%3600000)/60000),s=Math.floor((r%60000)/1000);e.textContent=`Time remaining: ${h}h ${m}m ${s}s`;}tick();setInterval(tick,1000);</script>@endif
@endsection
