<section class="relative overflow-hidden bg-gradient-to-br from-gray-900 to-gray-800">
    <div class="absolute inset-0 z-0 opacity-20 pointer-events-none">
        <svg class="w-full h-full" viewBox="0 0 800 800" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><circle cx="200" cy="150" r="250" fill="#3B82F6"/><circle cx="700" cy="650" r="300" fill="#10B981"/></svg>
    </div>
    <div class="relative z-10 px-4 py-16 mx-auto max-w-7xl sm:px-6 lg:px-8 text-center">
        <span class="inline-block px-3 py-1 mb-4 text-xs font-semibold tracking-wider text-blue-400 uppercase bg-blue-900 bg-opacity-30 rounded-full">{{ $topic }}</span>
        <h1 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl md:text-5xl">{{ $headline }}</h1>
        <p class="max-w-2xl mt-5 mx-auto text-xl text-gray-300">{{ $intro }}</p>
        <div class="flex flex-wrap justify-center gap-4 mt-8">
            <a href="{{ route('register') }}" class="px-6 py-3 font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">Create Account</a>
            <a href="{{ route('faq') }}" class="px-6 py-3 font-medium text-gray-200 border border-gray-700 rounded-lg hover:bg-gray-700">NFT FAQs</a>
        </div>
    </div>
</section>
<section class="py-16 bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-white text-center mb-12">Explore {{ $topic }}</h2>
        <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($cards as $card)
                <div class="bg-gray-800 bg-opacity-80 rounded-xl border border-gray-700 p-6 transition-all duration-300 hover:border-blue-500">
                    <h3 class="text-xl font-bold text-blue-400 mb-4">{{ $card[0] }}</h3>
                    <p class="text-gray-300 leading-relaxed">{{ $card[1] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
<section class="py-16 bg-gray-800">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold text-white mb-6">{{ $closingTitle }}</h2>
        <p class="text-gray-300 leading-relaxed">{{ $closing }}</p>
        <p class="mt-6 text-gray-300">Explore digital art NFT investment with {{ $settings->site_name }}.</p>
        <a href="{{ route('contact') }}" class="inline-block mt-8 px-6 py-3 text-white bg-blue-600 rounded-lg hover:bg-blue-700">Contact Our Team</a>
    </div>
</section>
