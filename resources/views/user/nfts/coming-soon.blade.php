@extends('layouts.dasht')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 py-16 text-center">
    <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-8 sm:p-12">
        <i data-lucide="image" class="w-12 h-12 mx-auto text-blue-500 mb-5"></i>
        <h1 class="text-3xl font-semibold text-gray-900 dark:text-white">{{ $title }}</h1>
        <p class="mt-4 text-gray-600 dark:text-gray-400">{{ $message }}</p>
        <a href="{{ route('user.nfts.index') }}" class="inline-flex items-center mt-8 px-6 py-3 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-medium">Explore Available NFTs</a>
    </div>
</div>
@endsection
