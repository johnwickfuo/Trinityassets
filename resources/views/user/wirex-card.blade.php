@extends('layouts.dasht')

@section('content')
@php
    $cardCurrencySymbol = $order ? $order->currency_symbol : Auth::user()->currency;
    $cardCurrencyCode = $order ? $order->currency_code : Auth::user()->s_currency;
@endphp
<div class="min-h-screen bg-gray-50 py-8 dark:bg-gray-900">
    <div class="mx-auto max-w-5xl px-4 sm:px-6">
        @if(session('success'))<div class="mb-6 rounded-xl bg-green-50 p-4 text-green-700 dark:bg-green-900/20 dark:text-green-300">{{ session('success') }}</div>@endif
        <div class="overflow-hidden rounded-3xl bg-gradient-to-br from-slate-950 via-blue-950 to-indigo-950 p-7 text-white shadow-2xl sm:p-10">
            <div class="grid gap-10 lg:grid-cols-2 lg:items-center">
                <div>
                    <span class="inline-flex rounded-full bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-blue-200">Optional account feature</span>
                    <h1 class="mt-5 text-4xl font-bold">Wirex Card</h1>
                    <p class="mt-4 leading-relaxed text-slate-300">Purchase multi-currency card access using your selected account currency. Card ownership is optional and does not affect your ability to request a withdrawal.</p>
                    <div class="mt-7 space-y-3 rounded-2xl bg-white/10 p-5 ring-1 ring-white/10">
                        <div class="flex justify-between"><span class="text-slate-300">Card access</span><strong>{{ $cardCurrencySymbol }}{{ number_format(1700, 2) }}</strong></div>
                        <div class="flex justify-between"><span class="text-slate-300">Activation</span><strong>{{ $cardCurrencySymbol }}{{ number_format(1700, 2) }}</strong></div>
                        <div class="flex justify-between border-t border-white/20 pt-3 text-lg"><span>Total</span><strong>{{ $cardCurrencySymbol }}{{ number_format(3400, 2) }} {{ $cardCurrencyCode }}</strong></div>
                    </div>
                </div>
                <div>
                    <div class="mx-auto aspect-[1.586/1] max-w-md rounded-3xl bg-gradient-to-br from-blue-500 via-indigo-600 to-purple-700 p-7 shadow-2xl ring-1 ring-white/30">
                        <div class="flex items-start justify-between"><div class="text-xl font-bold">WIREX</div><i data-lucide="contactless" class="h-7 w-7"></i></div>
                        <div class="mt-14 font-mono text-xl tracking-[0.18em]">{{ $order && $order->card_code ? $order->card_code : '•••• •••• ••••' }}</div>
                        <div class="mt-8 flex items-end justify-between"><div><p class="text-xs uppercase text-blue-100">Card holder</p><p class="mt-1 font-semibold uppercase">{{ Auth::user()->name }}</p></div><div class="rounded-lg bg-white/20 px-3 py-2 text-sm font-semibold">{{ $cardCurrencyCode }}</div></div>
                    </div>
                    <div class="mt-6 text-center">
                        @if($order && $order->status === 'active')
                            <span class="inline-flex items-center gap-2 rounded-full bg-green-500/20 px-4 py-2 font-semibold text-green-300"><i data-lucide="badge-check" class="h-5 w-5"></i> Active</span>
                            <p class="mt-3 text-sm text-slate-300">Activated {{ $order->activated_at->format('M d, Y h:i A') }}</p>
                        @elseif($order && $order->status === 'payment_pending')
                            <span class="inline-flex items-center gap-2 rounded-full bg-amber-500/20 px-4 py-2 font-semibold text-amber-200"><i data-lucide="clock" class="h-5 w-5"></i> Payment under review</span>
                            <p class="mt-3 text-sm text-slate-300">Your card activates after the linked deposit is approved.</p>
                        @else
                            <form method="POST" action="{{ route('user.wirex-card.purchase') }}">@csrf<button class="w-full rounded-xl bg-white px-5 py-3 font-bold text-blue-900 transition hover:bg-blue-50">Buy and Activate Card</button></form>
                            <p class="mt-3 text-xs text-slate-400">You will choose a deposit method next. The amount will be fixed at 3,400 units.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
