@php $bg = Auth('admin')->user()->dashboard_style === 'light' ? 'light' : 'dark'; $text = $bg === 'light' ? 'dark' : 'light'; @endphp
@extends('layouts.app')
@section('content')
@include('admin.topmenu')
@include('admin.sidebar')
<div class="main-panel"><div class="content"><div class="page-inner">
    <div class="page-header"><h4 class="page-title">NFT Conversions</h4></div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
    <div class="mb-4"><a href="{{ route('admin.nft-conversions.index') }}" class="btn btn-sm {{ !$status ? 'btn-primary' : 'btn-outline-primary' }}">All</a> @foreach(['pending','approved','rejected'] as $filter)<a href="{{ route('admin.nft-conversions.index', ['status'=>$filter]) }}" class="btn btn-sm {{ $status === $filter ? 'btn-primary' : 'btn-outline-primary' }}">{{ ucfirst($filter) }}</a> @endforeach</div>
    <div class="card bg-{{ $bg }}"><div class="card-body table-responsive p-0"><table class="table mb-0"><thead><tr><th>User</th><th>Reference</th><th>Conversion</th><th>Fee</th><th>Deadline</th><th>Status</th><th>Actions</th></tr></thead><tbody>
        @forelse($conversions as $conversion)<tr><td class="text-{{ $text }}">{{ $conversion->user ? $conversion->user->name : 'Deleted user' }}<br><small>{{ $conversion->user ? $conversion->user->email : '' }}</small></td><td class="text-{{ $text }}">{{ $conversion->reference }}</td><td class="text-{{ $text }}">{{ $conversion->from_currency }} {{ number_format($conversion->nft_amount,2) }}<br><small>→ {{ $conversion->to_currency }} {{ number_format($conversion->converted_amount,2) }}</small></td><td class="text-{{ $text }}">{{ $conversion->to_currency }} {{ number_format($conversion->fee_amount,2) }}<br><small class="text-capitalize">{{ $conversion->fee_status }}</small></td><td class="text-{{ $text }}">{{ $conversion->expires_at->format('M d, Y h:i A') }}</td><td><span class="badge {{ $conversion->status === 'approved' ? 'badge-success' : ($conversion->status === 'rejected' ? 'badge-danger' : 'badge-warning') }}">{{ ucfirst($conversion->status) }}</span></td><td style="min-width:190px">
            @if($conversion->status === 'pending')
                <form method="POST" action="{{ route('admin.nft-conversions.approve',$conversion) }}" class="mb-2" onsubmit="return confirm('Approve this conversion and waive the fee?')">@csrf<button class="btn btn-sm btn-success btn-block">Approve / Waive Fee</button></form>
                <form method="POST" action="{{ route('admin.nft-conversions.reject',$conversion) }}">@csrf<input name="reason" maxlength="1000" class="form-control form-control-sm mb-1" placeholder="Rejection reason"><button class="btn btn-sm btn-danger btn-block">Reject & Refund</button></form>
            @else<small class="text-{{ $text }}">Processed {{ optional($conversion->approved_at ?: $conversion->rejected_at)->format('M d, Y') }}</small>@endif
        </td></tr>@empty<tr><td colspan="7" class="text-center p-4 text-{{ $text }}">No conversions found.</td></tr>@endforelse
    </tbody></table></div>@if($conversions->hasPages())<div class="card-footer">{{ $conversions->links() }}</div>@endif</div>
</div></div></div>
@endsection
