@if(session('withdrawal_blocked_message'))
<div id="withdrawal-blocked-popup" role="dialog" aria-modal="true" aria-labelledby="withdrawal-blocked-title" class="fixed inset-0 z-[10000] flex items-center justify-center bg-gray-950/70 px-4 py-8 backdrop-blur-sm">
    <section class="relative w-full max-w-lg overflow-hidden rounded-2xl bg-white shadow-2xl dark:bg-gray-800">
        <div class="h-2 bg-gradient-to-r from-red-600 via-orange-500 to-amber-500"></div>
        <button type="button" aria-label="Close notification" onclick="document.getElementById('withdrawal-blocked-popup').remove()" class="absolute right-4 top-5 rounded-full p-2 text-gray-400 transition hover:bg-gray-100 hover:text-gray-700 dark:hover:bg-gray-700 dark:hover:text-white">
            <i data-lucide="x" class="h-5 w-5"></i>
        </button>
        <div class="px-6 pb-6 pt-8 sm:px-8 sm:pb-8">
            <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-full bg-red-100 text-red-600 dark:bg-red-900/40 dark:text-red-300"><i data-lucide="shield-alert" class="h-6 w-6"></i></div>
            <h2 id="withdrawal-blocked-title" class="pr-10 text-2xl font-bold text-gray-900 dark:text-white">Withdrawals Temporarily Restricted</h2>
            <p class="mt-4 whitespace-pre-line break-words leading-relaxed text-gray-600 dark:text-gray-300">{{ session('withdrawal_blocked_message') }}</p>
            <div class="mt-7 flex justify-end"><button type="button" onclick="document.getElementById('withdrawal-blocked-popup').remove()" class="rounded-xl bg-red-600 px-5 py-3 font-semibold text-white transition hover:bg-red-700">Close</button></div>
        </div>
    </section>
</div>
@endif
