@extends('layouts.guest1')
@section('title', 'Create Account')
@section('content')

<div class="relative min-h-screen overflow-hidden bg-gray-900 px-4 py-8 sm:px-6 sm:py-12">
    <div class="pointer-events-none absolute inset-0">
        <div class="absolute -left-32 top-12 h-72 w-72 rounded-full bg-blue-600/10 blur-3xl"></div>
        <div class="absolute -right-32 bottom-12 h-72 w-72 rounded-full bg-cyan-500/10 blur-3xl"></div>
    </div>

    <div class="relative z-10 mx-auto w-full max-w-xl">
        <div class="rounded-2xl border border-gray-700 bg-gray-900 p-6 shadow-2xl sm:rounded-3xl sm:p-10" x-data="{ showPassword: false, submitting: false }">
            <div class="mb-8 text-center">
                <img src="{{ asset('storage/app/public/'.$settings->logo) }}" class="mx-auto mb-6 h-14 w-auto sm:h-16" alt="{{ $settings->site_name }}">
                <h1 class="text-3xl font-bold text-white sm:text-4xl">Create your account</h1>
                <p class="mt-3 text-sm text-gray-400 sm:text-base">Start your digital art NFT investment journey with {{ $settings->site_name }}.</p>
            </div>

            @if($errors->any())
                <div class="mb-6 rounded-xl border border-red-500/30 bg-red-500/10 p-4 text-sm text-red-300" role="alert">
                    <p class="font-bold">Please correct the following:</p>
                    <ul class="mt-2 list-disc space-y-1 pl-5">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST" class="space-y-5" @submit="submitting = true">
                @csrf

                <div>
                    <label for="name" class="mb-2 block text-sm font-bold text-gray-200">Full Name</label>
                    <div class="relative">
                        <i data-lucide="user-check" class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required autocomplete="name" maxlength="255"
                            class="block w-full rounded-xl border border-gray-600 bg-gray-800 py-4 pl-12 pr-4 text-sm font-semibold text-white placeholder-gray-500 transition focus:border-blue-400 focus:ring-2 focus:ring-blue-400/20"
                            placeholder="Enter your full name">
                    </div>
                    @error('name')<p class="mt-2 text-sm text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="username" class="mb-2 block text-sm font-bold text-gray-200">Username</label>
                    <div class="relative">
                        <i data-lucide="at-sign" class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" name="username" id="username" value="{{ old('username') }}" required autocomplete="username" maxlength="191"
                            class="block w-full rounded-xl border border-gray-600 bg-gray-800 py-4 pl-12 pr-4 text-sm font-semibold text-white placeholder-gray-500 transition focus:border-blue-400 focus:ring-2 focus:ring-blue-400/20"
                            placeholder="Choose a username">
                    </div>
                    @error('username')<p class="mt-2 text-sm text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="email" class="mb-2 block text-sm font-bold text-gray-200">Email</label>
                    <div class="relative">
                        <i data-lucide="mail" class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400"></i>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required autocomplete="email" maxlength="255"
                            class="block w-full rounded-xl border border-gray-600 bg-gray-800 py-4 pl-12 pr-4 text-sm font-semibold text-white placeholder-gray-500 transition focus:border-blue-400 focus:ring-2 focus:ring-blue-400/20"
                            placeholder="you@example.com">
                    </div>
                    @error('email')<p class="mt-2 text-sm text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="currency_code" class="mb-2 block text-sm font-bold text-gray-200">Currency</label>
                    <div class="relative">
                        <i data-lucide="coins" class="pointer-events-none absolute left-4 top-1/2 z-10 h-5 w-5 -translate-y-1/2 text-gray-400"></i>
                        <select name="currency_code" id="currency_code" required
                            class="block w-full appearance-none rounded-xl border border-gray-600 bg-gray-800 py-4 pl-12 pr-10 text-sm font-semibold text-white transition focus:border-blue-400 focus:ring-2 focus:ring-blue-400/20">
                            @foreach(\App\Support\SupportedCurrencies::all() as $code => $currency)
                                <option value="{{ $code }}" {{ old('currency_code', 'TTD') === $code ? 'selected' : '' }}>{{ $currency['name'] }} ({{ $code }} — {{ $currency['symbol'] }})</option>
                            @endforeach
                        </select>
                        <i data-lucide="chevron-down" class="pointer-events-none absolute right-4 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400"></i>
                    </div>
                    @error('currency_code')<p class="mt-2 text-sm text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password" class="mb-2 block text-sm font-bold text-gray-200">Password</label>
                    <div class="relative">
                        <i data-lucide="lock" class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400"></i>
                        <input :type="showPassword ? 'text' : 'password'" name="password" id="password" required autocomplete="new-password" minlength="8"
                            class="block w-full rounded-xl border border-gray-600 bg-gray-800 py-4 pl-12 pr-12 text-sm font-semibold text-white placeholder-gray-500 transition focus:border-blue-400 focus:ring-2 focus:ring-blue-400/20"
                            placeholder="At least 8 characters with a number">
                        <button type="button" @click="showPassword = !showPassword" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 transition hover:text-white" :aria-label="showPassword ? 'Hide password' : 'Show password'">
                            <i x-show="!showPassword" data-lucide="eye" class="h-5 w-5"></i>
                            <i x-show="showPassword" data-lucide="eye-off" class="h-5 w-5"></i>
                        </button>
                    </div>
                    @error('password')<p class="mt-2 text-sm text-red-400">{{ $message }}</p>@enderror
                </div>

                @if(Session::has('ref_by'))
                    <input type="hidden" name="ref_by" value="{{ session('ref_by') }}">
                @endif

                <button type="submit" :disabled="submitting"
                    class="mt-2 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-cyan-600 px-6 py-4 font-bold text-white shadow-lg transition hover:from-blue-700 hover:to-cyan-700 disabled:cursor-not-allowed disabled:opacity-60">
                    <i x-show="!submitting" data-lucide="user-plus" class="h-5 w-5"></i>
                    <svg x-show="submitting" class="h-5 w-5 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <span x-text="submitting ? 'Creating Account...' : 'Create Account'">Create Account</span>
                </button>
            </form>

            <p class="mt-7 text-center text-sm text-gray-400">Already have an account? <a href="{{ route('login') }}" class="font-bold text-blue-400 transition hover:text-blue-300">Sign in</a></p>
            <div class="mt-6 flex items-center justify-center gap-5 border-t border-gray-700 pt-5 text-xs text-gray-500">
                <span class="inline-flex items-center gap-1"><i data-lucide="shield-check" class="h-4 w-4"></i> Secure signup</span>
                <span class="inline-flex items-center gap-1"><i data-lucide="lock" class="h-4 w-4"></i> Encrypted</span>
            </div>
        </div>
    </div>
</div>

<style>
    .skiptranslate { display: none !important; }
    body { top: 0 !important; }
</style>

@endsection
