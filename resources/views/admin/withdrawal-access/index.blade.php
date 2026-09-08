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
            <div class="page-header"><h4 class="page-title">Withdrawal Access</h4></div>

            @if(session('success'))<div class="alert alert-success" role="status">{{ session('success') }}</div>@endif
            @if($errors->any())
                <div class="alert alert-danger" role="alert"><strong>Please check the withdrawal access details.</strong><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
            @endif

            <div class="row">
                <div class="col-lg-6">
                    <div class="card bg-{{ $bg }}">
                        <div class="card-header">
                            <h2 class="card-title text-{{ $text }}">Set user withdrawal access</h2>
                            <p class="text-{{ $text }} mb-0">Disable withdrawals while a user's KYC or account activity is being reviewed, or enable them again.</p>
                        </div>
                        <form method="POST" action="{{ route('admin.withdrawal-access.update') }}" id="withdrawal-access-form">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="withdrawal-user" class="text-{{ $text }}">User</label>
                                    <select id="withdrawal-user" name="user_id" required class="form-control select2" style="width:100%" data-placeholder="Choose a user">
                                        <option value=""></option>
                                        @foreach($users as $user)<option value="{{ $user->id }}" {{ (string) old('user_id') === (string) $user->id ? 'selected' : '' }}>{{ $user->name }} — {{ $user->email }}</option>@endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="withdrawal-action" class="text-{{ $text }}">Withdrawal status</label>
                                    <select id="withdrawal-action" name="action" required class="form-control bg-{{ $bg }} text-{{ $text }}">
                                        <option value="block" {{ old('action', 'block') === 'block' ? 'selected' : '' }}>Block withdrawals</option>
                                        <option value="enable" {{ old('action') === 'enable' ? 'selected' : '' }}>Enable withdrawals</option>
                                    </select>
                                </div>
                                <div class="form-group" id="blocking-message-group">
                                    <label for="blocking-message" class="text-{{ $text }}">Message shown to user</label>
                                    <textarea id="blocking-message" name="message" rows="5" maxlength="2000" class="form-control bg-{{ $bg }} text-{{ $text }}" placeholder="Explain why withdrawals are unavailable and what the user should do next.">{{ old('message') }}</textarea>
                                    <small class="form-text text-{{ $text }}">Required when withdrawals are blocked. The user sees this in a closable popup when they try to withdraw.</small>
                                </div>
                            </div>
                            <div class="card-action"><button type="submit" class="btn btn-primary">Save Withdrawal Access</button></div>
                        </form>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card bg-{{ $bg }}">
                        <div class="card-header"><h2 class="card-title text-{{ $text }}">Users with blocked withdrawals</h2></div>
                        <div class="card-body p-0">
                            @forelse($blockedUsers as $restriction)
                                <div class="p-3 border-bottom">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="pr-3">
                                            <strong class="text-{{ $text }}">{{ optional($restriction->user)->name ?: 'Deleted user' }}</strong>
                                            @if($restriction->user)<div class="small text-{{ $text }}">{{ $restriction->user->email }}</div>@endif
                                            <p class="small text-{{ $text }} mt-2 mb-1">{{ $restriction->message }}</p>
                                            <small class="text-{{ $text }}">Blocked {{ optional($restriction->blocked_at)->format('M d, Y h:i A') }}</small>
                                        </div>
                                        @if($restriction->user)
                                            <form method="POST" action="{{ route('admin.withdrawal-access.update') }}">
                                                @csrf
                                                <input type="hidden" name="user_id" value="{{ $restriction->user_id }}">
                                                <input type="hidden" name="action" value="enable">
                                                <button type="submit" class="btn btn-sm btn-success">Enable</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="p-4 text-{{ $text }}">No users currently have blocked withdrawals.</div>
                            @endforelse
                        </div>
                        @if($blockedUsers->hasPages())<div class="card-footer">{{ $blockedUsers->links() }}</div>@endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(function () {
    const action = $('#withdrawal-action');
    const message = $('#blocking-message');
    const messageGroup = $('#blocking-message-group');
    const updateMessage = function () {
        const blocking = action.val() === 'block';
        message.prop('required', blocking);
        messageGroup.toggle(blocking);
    };

    $('#withdrawal-user').select2({ placeholder: $('#withdrawal-user').data('placeholder') });
    action.on('change', updateMessage);
    updateMessage();
});
</script>
@endsection
