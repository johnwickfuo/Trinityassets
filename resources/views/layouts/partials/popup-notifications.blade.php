@php
    $popupRecipients = \App\Models\PopupNotificationRecipient::where('user_id', Auth::id())
        ->whereNull('dismissed_at')
        ->whereHas('notification', function ($query) {
            $query->active();
        })
        ->with('notification')
        ->latest('id')
        ->limit(10)
        ->get();
@endphp

@if($popupRecipients->isNotEmpty())
<div x-data="{
        current: 0,
        total: {{ $popupRecipients->count() }},
        busy: false,
        async dismiss(form) {
            this.busy = true;
            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    body: new FormData(form)
                });
                if (!response.ok) throw new Error('Unable to dismiss notification');
                this.current++;
            } catch (error) {
                form.submit();
            } finally {
                this.busy = false;
            }
        }
    }"
    x-show="current < total"
    x-cloak
    class="fixed inset-0 z-[9999] flex items-center justify-center bg-gray-950/70 px-4 py-8 backdrop-blur-sm">
    @foreach($popupRecipients as $position => $recipient)
        @php
            $popup = $recipient->notification;
            $actions = [
                'deposit' => ['label' => 'Deposit', 'url' => route('deposits')],
                'buy_nft' => ['label' => 'Buy NFT', 'url' => route('user.nfts.index')],
                'my_nfts' => ['label' => 'View My NFTs', 'url' => route('user.nfts.collection')],
            ];
            $action = $popup->action_type ? ($actions[$popup->action_type] ?? null) : null;
        @endphp
        <section x-show="current === {{ $position }}" x-transition role="dialog" aria-modal="true" aria-labelledby="popup-title-{{ $recipient->id }}" class="relative w-full max-w-lg overflow-hidden rounded-2xl bg-white shadow-2xl dark:bg-gray-800">
            <div class="h-2 bg-gradient-to-r from-blue-600 via-indigo-500 to-purple-600"></div>
            <form method="POST" action="{{ route('user.popup-notifications.dismiss', $recipient) }}" @submit.prevent="dismiss($event.currentTarget)">
                @csrf
                <button type="submit" :disabled="busy" aria-label="Close notification" class="absolute right-4 top-5 rounded-full p-2 text-gray-400 transition hover:bg-gray-100 hover:text-gray-700 dark:hover:bg-gray-700 dark:hover:text-white">
                    <i data-lucide="x" class="h-5 w-5"></i>
                </button>
                <div class="px-6 pb-6 pt-8 sm:px-8 sm:pb-8">
                    <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900/40 dark:text-blue-300"><i data-lucide="bell" class="h-6 w-6"></i></div>
                    <h2 id="popup-title-{{ $recipient->id }}" class="pr-10 text-2xl font-bold text-gray-900 dark:text-white">{{ $popup->title }}</h2>
                    <p class="mt-4 whitespace-pre-line break-words leading-relaxed text-gray-600 dark:text-gray-300">{{ $popup->message }}</p>
                    <p class="mt-5 text-xs text-gray-400">Available until {{ $popup->expires_at->format('M d, Y h:i A') }}</p>
                    <div class="mt-7 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                        <button type="submit" :disabled="busy" class="rounded-xl border border-gray-300 px-5 py-3 font-semibold text-gray-700 transition hover:bg-gray-50 disabled:opacity-60 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">Close</button>
                        @if($action)<a href="{{ $action['url'] }}" class="rounded-xl bg-blue-600 px-5 py-3 text-center font-semibold text-white transition hover:bg-blue-700">{{ $action['label'] }}</a>@endif
                    </div>
                    @if($popupRecipients->count() > 1)<p class="mt-4 text-center text-xs text-gray-400">Notification {{ $position + 1 }} of {{ $popupRecipients->count() }}</p>@endif
                </div>
            </form>
        </section>
    @endforeach
</div>
@endif
