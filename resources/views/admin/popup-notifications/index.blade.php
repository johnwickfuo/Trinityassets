@php
    $bg = Auth('admin')->user()->dashboard_style === 'light' ? 'light' : 'dark';
    $text = $bg === 'light' ? 'dark' : 'light';
@endphp
@extends('layouts.app')

@section('content')
@include('admin.topmenu')
@include('admin.sidebar')
<div class="main-panel">
    <div class="content">
        <div class="page-inner">
            <div class="page-header"><h4 class="page-title">Send Notification</h4></div>

            @if (session('success'))<div class="alert alert-success" role="status">{{ session('success') }}</div>@endif
            @if ($errors->any())
                <div class="alert alert-danger" role="alert"><strong>Please check the notification details.</strong><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
            @endif

            <div class="row">
                <div class="col-lg-7">
                    <div class="card bg-{{ $bg }}">
                        <div class="card-header"><h2 class="card-title text-{{ $text }}">Create popup notification</h2><p class="text-{{ $text }} mb-0">The popup remains available until the selected duration ends or the user closes it.</p></div>
                        <form method="POST" action="{{ route('admin.popup-notifications.store') }}">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="notification-users" class="text-{{ $text }}">Recipients</label>
                                    <select id="notification-users" name="user_ids[]" multiple required class="form-control select2" style="width:100%" data-placeholder="Select one or more users">
                                        @foreach($users as $user)<option value="{{ $user->id }}" {{ collect(old('user_ids', []))->contains($user->id) ? 'selected' : '' }}>{{ $user->name }} — {{ $user->email }}</option>@endforeach
                                    </select>
                                    <small class="form-text text-{{ $text }}"><span id="recipient-count">0</span> user(s) selected</small>
                                </div>
                                <div class="form-group"><label for="notification-title" class="text-{{ $text }}">Title</label><input id="notification-title" name="title" value="{{ old('title') }}" maxlength="150" required class="form-control bg-{{ $bg }} text-{{ $text }}" placeholder="Important account update"></div>
                                <div class="form-group"><label for="notification-message" class="text-{{ $text }}">Message</label><textarea id="notification-message" name="message" rows="6" maxlength="5000" required class="form-control bg-{{ $bg }} text-{{ $text }}" placeholder="Enter the message users should see.">{{ old('message') }}</textarea></div>
                                <div class="row">
                                    <div class="col-md-6 form-group"><label for="duration-days" class="text-{{ $text }}">Display duration in days</label><input id="duration-days" name="duration_days" type="number" value="{{ old('duration_days', 1) }}" min="1" max="365" required class="form-control bg-{{ $bg }} text-{{ $text }}"><small class="form-text text-{{ $text }}">Between 1 and 365 days.</small></div>
                                    <div class="col-md-6 form-group"><label for="notification-action" class="text-{{ $text }}">Optional button</label><select id="notification-action" name="action_type" class="form-control bg-{{ $bg }} text-{{ $text }}"><option value="">No button</option><option value="deposit" {{ old('action_type') === 'deposit' ? 'selected' : '' }}>Deposit</option><option value="buy_nft" {{ old('action_type') === 'buy_nft' ? 'selected' : '' }}>Buy NFT</option><option value="my_nfts" {{ old('action_type') === 'my_nfts' ? 'selected' : '' }}>View My NFTs</option></select></div>
                                </div>
                            </div>
                            <div class="card-action"><button type="submit" class="btn btn-primary">Send Notification</button></div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="card bg-{{ $bg }}">
                        <div class="card-header"><h2 class="card-title text-{{ $text }}">Recently sent</h2></div>
                        <div class="card-body p-0">
                            @forelse($notifications as $notification)
                                <div class="p-3 border-bottom"><div class="d-flex justify-content-between"><strong class="text-{{ $text }}">{{ $notification->title }}</strong><span class="badge {{ $notification->expires_at->isFuture() ? 'badge-success' : 'badge-secondary' }}">{{ $notification->expires_at->isFuture() ? 'Active' : 'Expired' }}</span></div><p class="text-{{ $text }} small mb-1">{{ \Illuminate\Support\Str::limit($notification->message, 100) }}</p><small class="text-{{ $text }}">{{ $notification->recipients_count }} recipient(s) · Expires {{ $notification->expires_at->format('M d, Y h:i A') }}</small></div>
                            @empty<div class="p-4 text-{{ $text }}">No popup notifications sent yet.</div>@endforelse
                        </div>
                        @if($notifications->hasPages())<div class="card-footer">{{ $notifications->links() }}</div>@endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(function () {
    const select = $('#notification-users');
    const updateCount = () => $('#recipient-count').text((select.val() || []).length);
    select.select2({ placeholder: select.data('placeholder'), closeOnSelect: false });
    select.on('change', updateCount);
    updateCount();
});
</script>
@endsection
