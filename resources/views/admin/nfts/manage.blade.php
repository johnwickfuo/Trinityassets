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
            <div class="page-header d-flex flex-wrap justify-content-between align-items-center">
                <div>
                    <h4 class="page-title">Manage NFTs</h4>
                    <p class="text-{{ $text }} mb-0">Edit NFT details and create bids for NFTs owned by users.</p>
                </div>
                <a href="{{ route('admin.nfts.create') }}" class="btn btn-primary mt-2 mt-sm-0">Upload New NFT</a>
            </div>

            @if (session('success'))
                <div class="alert alert-success" role="status">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <strong>Please check the submitted details.</strong>
                    <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <div class="card bg-{{ $bg }} mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.nfts.manage') }}" class="form-inline">
                        <label class="sr-only" for="nft-search">Search NFTs</label>
                        <input id="nft-search" name="search" value="{{ $search }}" type="search" class="form-control mr-2 flex-grow-1 bg-{{ $bg }} text-{{ $text }}" placeholder="Search by NFT name, owner name or email">
                        <button type="submit" class="btn btn-primary">Search</button>
                        @if ($search !== '')<a href="{{ route('admin.nfts.manage') }}" class="btn btn-link">Clear</a>@endif
                    </form>
                </div>
            </div>

            <div class="row">
                @forelse ($nfts as $nft)
                    <div class="col-lg-6 mb-4">
                        <article class="card h-100 bg-{{ $bg }}">
                            <div class="row no-gutters">
                                <div class="col-md-4">
                                    <img src="{{ route('admin.nfts.image', $nft) }}" alt="{{ $nft->name }}" loading="lazy" style="width:100%;height:220px;object-fit:cover;" class="rounded-left">
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <h2 class="h5 text-{{ $text }} mb-2" style="overflow-wrap:anywhere;">{{ $nft->name }}</h2>
                                            <span class="badge {{ $nft->owner ? 'badge-info' : ($nft->is_available ? 'badge-success' : 'badge-secondary') }}">{{ $nft->owner ? 'Owned' : ($nft->is_available ? 'Available' : 'Hidden') }}</span>
                                        </div>
                                        <p class="text-{{ $text }} mb-1"><strong>Price:</strong> {{ $nft->currency }} {{ number_format($nft->price, 2) }}</p>
                                        <p class="text-{{ $text }} small mb-1"><strong>Projected:</strong> {{ $nft->currency }} {{ number_format($nft->projected_min_value, 2) }} – {{ number_format($nft->projected_max_value, 2) }}</p>
                                        <p class="text-{{ $text }} small mb-3"><strong>Owner:</strong> {{ $nft->owner ? $nft->owner->name.' ('.$nft->owner->email.')' : 'No owner' }}</p>
                                        <button class="btn btn-sm btn-outline-primary" type="button" data-toggle="collapse" data-target="#edit-nft-{{ $nft->id }}" aria-expanded="false" aria-controls="edit-nft-{{ $nft->id }}">Edit NFT</button>
                                    </div>
                                </div>
                            </div>

                            <div class="collapse" id="edit-nft-{{ $nft->id }}">
                                <div class="card-body border-top">
                                    <form method="POST" action="{{ route('admin.nfts.update', $nft) }}" enctype="multipart/form-data">
                                        @csrf @method('PUT')
                                        <div class="form-group"><label for="name-{{ $nft->id }}" class="text-{{ $text }}">NFT name</label><input id="name-{{ $nft->id }}" name="name" value="{{ $nft->name }}" maxlength="120" required class="form-control bg-{{ $bg }} text-{{ $text }}"></div>
                                        <div class="form-group"><label for="description-{{ $nft->id }}" class="text-{{ $text }}">Description</label><textarea id="description-{{ $nft->id }}" name="description" maxlength="2000" rows="3" class="form-control bg-{{ $bg }} text-{{ $text }}">{{ $nft->description }}</textarea></div>
                                        <div class="row">
                                            <div class="col-sm-4 form-group"><label for="price-{{ $nft->id }}" class="text-{{ $text }}">Price</label><input id="price-{{ $nft->id }}" name="price" type="number" value="{{ $nft->price }}" min="0.01" max="9999999999.99" step="0.01" required class="form-control bg-{{ $bg }} text-{{ $text }}"></div>
                                            <div class="col-sm-4 form-group"><label for="min-{{ $nft->id }}" class="text-{{ $text }}">Projected from</label><input id="min-{{ $nft->id }}" name="projected_min_value" type="number" value="{{ $nft->projected_min_value }}" min="0.01" max="9999999999.99" step="0.01" required class="form-control bg-{{ $bg }} text-{{ $text }}"></div>
                                            <div class="col-sm-4 form-group"><label for="max-{{ $nft->id }}" class="text-{{ $text }}">Projected to</label><input id="max-{{ $nft->id }}" name="projected_max_value" type="number" value="{{ $nft->projected_max_value }}" min="0.01" max="9999999999.99" step="0.01" required class="form-control bg-{{ $bg }} text-{{ $text }}"></div>
                                        </div>
                                        <div class="form-group"><label for="image-{{ $nft->id }}" class="text-{{ $text }}">Replace image <span class="font-weight-normal">(optional)</span></label><input id="image-{{ $nft->id }}" name="image" type="file" accept="image/jpeg,image/png,image/webp,image/gif" class="form-control-file text-{{ $text }}"><small class="text-{{ $text }}">JPG, PNG, WebP or GIF, up to 4 MB.</small></div>
                                        @if (!$nft->owner)
                                            <div class="form-check mb-2"><input id="available-{{ $nft->id }}" name="is_available" type="checkbox" value="1" {{ $nft->is_available ? 'checked' : '' }} class="form-check-input"><label for="available-{{ $nft->id }}" class="form-check-label text-{{ $text }}">Show in Buy NFT catalogue</label></div>
                                        @endif
                                        <div class="form-check mb-3"><input id="auto-{{ $nft->id }}" name="auto_bid_enabled" type="checkbox" value="1" {{ $nft->auto_bid_enabled ? 'checked' : '' }} class="form-check-input"><label for="auto-{{ $nft->id }}" class="form-check-label text-{{ $text }}">Enable daily automatic bids after purchase</label></div>
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </form>
                                </div>
                            </div>

                            @if ($nft->owner)
                                <div class="card-body border-top">
                                    <h3 class="h6 text-{{ $text }}">Bid Management</h3>
                                    <p class="text-{{ $text }} small">Current bid: <strong>{{ $nft->currentBid ? $nft->currency.' '.number_format($nft->currentBid->amount, 2) : 'None' }}</strong></p>
                                    <form method="POST" action="{{ route('admin.nfts.bids.manual', $nft) }}">
                                        @csrf
                                        <label class="sr-only" for="bid-{{ $nft->id }}">Manual bid amount</label>
                                        <div class="input-group"><div class="input-group-prepend"><span class="input-group-text">{{ $nft->currency }}</span></div><input id="bid-{{ $nft->id }}" name="amount" type="number" min="0.01" max="9999999999.99" step="0.01" required class="form-control" placeholder="Enter bid amount"><div class="input-group-append"><button type="submit" class="btn btn-success">Create Bid</button></div></div>
                                    </form>
                                    <div class="row mt-2">
                                        <div class="col-sm-6 mb-2 mb-sm-0"><form method="POST" action="{{ route('admin.nfts.bids.automatic', $nft) }}">@csrf<button type="submit" class="btn btn-sm btn-info btn-block">Generate Bid Now</button></form></div>
                                        <div class="col-sm-6"><form method="POST" action="{{ route('admin.nfts.automatic-bids', $nft) }}">@csrf @method('PATCH')<input type="hidden" name="enabled" value="{{ $nft->auto_bid_enabled ? 0 : 1 }}"><button type="submit" class="btn btn-sm btn-outline-secondary btn-block">{{ $nft->auto_bid_enabled ? 'Disable' : 'Enable' }} Daily Bids</button></form></div>
                                    </div>
                                </div>
                            @else
                                <div class="card-footer text-{{ $text }} small">Manual bids become available after a user purchases this NFT.</div>
                            @endif
                        </article>
                    </div>
                @empty
                    <div class="col-12"><div class="card bg-{{ $bg }}"><div class="card-body text-{{ $text }}">No NFTs match your search.</div></div></div>
                @endforelse
            </div>
            {{ $nfts->links() }}
        </div>
    </div>
</div>
@endsection
