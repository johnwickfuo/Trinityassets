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
            <div class="page-header">
                <h4 class="page-title">Upload NFT</h4>
            </div>

            @if (session('success'))
                <div class="alert alert-success" role="status">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <strong>Please check the upload details.</strong>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card bg-{{ $bg }}">
                <div class="card-header">
                    <h2 class="card-title text-{{ $text }}">Add artwork to the NFT catalogue</h2>
                    <p class="mb-0 text-{{ $text }}">Upload an image, name the artwork and set its price. It will appear on the user Buy NFT page.</p>
                </div>
                <form action="{{ route('admin.nfts.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nft-name" class="text-{{ $text }}">NFT name</label>
                                    <input id="nft-name" name="name" type="text" value="{{ old('name') }}" maxlength="120" required class="form-control bg-{{ $bg }} text-{{ $text }}">
                                </div>
                                <div class="form-group">
                                    <label for="nft-price" class="text-{{ $text }}">Price ({{ $settings->s_currency ?: $settings->currency }})</label>
                                    <input id="nft-price" name="price" type="number" value="{{ old('price') }}" min="0.01" max="9999999999.99" step="0.01" inputmode="decimal" required class="form-control bg-{{ $bg }} text-{{ $text }}" placeholder="0.00">
                                </div>
                                <div class="form-group">
                                    <label for="nft-description" class="text-{{ $text }}">Description <span class="font-weight-normal">(optional)</span></label>
                                    <textarea id="nft-description" name="description" rows="4" maxlength="2000" class="form-control bg-{{ $bg }} text-{{ $text }}" placeholder="Tell users about the artwork.">{{ old('description') }}</textarea>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 form-group">
                                        <label for="projected-min" class="text-{{ $text }}">Projected value from</label>
                                        <input id="projected-min" name="projected_min_value" type="number" value="{{ old('projected_min_value') }}" min="0.01" max="9999999999.99" step="0.01" required class="form-control bg-{{ $bg }} text-{{ $text }}">
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label for="projected-max" class="text-{{ $text }}">Projected value to</label>
                                        <input id="projected-max" name="projected_max_value" type="number" value="{{ old('projected_max_value') }}" min="0.01" max="9999999999.99" step="0.01" required class="form-control bg-{{ $bg }} text-{{ $text }}">
                                    </div>
                                </div>
                                <div class="form-check">
                                    <input id="auto-bids" name="auto_bid_enabled" type="checkbox" value="1" {{ old('auto_bid_enabled') ? 'checked' : '' }} class="form-check-input">
                                    <label for="auto-bids" class="form-check-label text-{{ $text }}">Generate daily automatic bids after purchase</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nft-image" class="text-{{ $text }}">NFT image</label>
                                    <input id="nft-image" name="image" type="file" accept="image/jpeg,image/png,image/webp,image/gif" required class="form-control-file text-{{ $text }}" aria-describedby="nft-image-help">
                                    <small id="nft-image-help" class="form-text text-{{ $text }}">JPG, PNG, WebP or GIF. Up to 4 MB and 6,000 × 6,000 pixels.</small>
                                    <img id="nft-image-preview" alt="Preview of the selected NFT artwork" hidden class="mt-3 rounded" style="max-width:100%;max-height:280px;object-fit:contain;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-action">
                        <button type="submit" class="btn btn-primary">Upload NFT</button>
                    </div>
                </form>
            </div>

            <h2 class="text-{{ $text }} h4 mb-3">Uploaded NFTs <span class="badge badge-primary">{{ $nfts->total() }}</span></h2>
            <div class="row">
                @forelse ($nfts as $nft)
                    <div class="col-sm-6 col-lg-4 col-xl-3 mb-4">
                        <article class="card h-100 bg-{{ $bg }}">
                            <img src="{{ route('admin.nfts.image', $nft) }}" alt="{{ $nft->name }}" loading="lazy" class="card-img-top" style="width:100%;height:220px;object-fit:cover;">
                            <div class="card-body">
                                <h3 class="h5 text-{{ $text }}" style="overflow-wrap:anywhere;">{{ $nft->name }}</h3>
                                <p class="text-{{ $text }} font-weight-bold mb-2">{{ $nft->currency }} {{ number_format($nft->price, 2) }}</p>
                                <p class="text-{{ $text }} small mb-2">Projected: {{ $nft->currency }} {{ number_format($nft->projected_min_value, 2) }} – {{ number_format($nft->projected_max_value, 2) }}</p>
                                <span class="badge {{ $nft->is_available ? 'badge-success' : 'badge-secondary' }}">{{ $nft->is_available ? 'Available' : 'Unavailable' }}</span>
                                @if ($nft->owner)
                                    <p class="text-{{ $text }} small mt-3 mb-2">Owner: {{ $nft->owner->email }}</p>
                                    @if ($nft->currentBid)
                                        <p class="text-{{ $text }} small">Current bid: <strong>{{ $nft->currency }} {{ number_format($nft->currentBid->amount, 2) }}</strong></p>
                                    @endif
                                    <form method="POST" action="{{ route('admin.nfts.bids.manual', $nft) }}" class="mt-3">
                                        @csrf
                                        <div class="input-group input-group-sm">
                                            <input name="amount" type="number" min="0.01" max="9999999999.99" step="0.01" required class="form-control" placeholder="Manual bid">
                                            <div class="input-group-append"><button class="btn btn-primary" type="submit">Add bid</button></div>
                                        </div>
                                    </form>
                                    <form method="POST" action="{{ route('admin.nfts.bids.automatic', $nft) }}" class="mt-2">
                                        @csrf
                                        <button class="btn btn-sm btn-info btn-block" type="submit">Generate in-range bid now</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.nfts.automatic-bids', $nft) }}" class="mt-2">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="enabled" value="{{ $nft->auto_bid_enabled ? 0 : 1 }}">
                                        <button class="btn btn-sm btn-outline-secondary btn-block" type="submit">{{ $nft->auto_bid_enabled ? 'Disable' : 'Enable' }} daily bids</button>
                                    </form>
                                @endif
                            </div>
                        </article>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card bg-{{ $bg }}"><div class="card-body text-{{ $text }}">No NFTs uploaded yet. Add your first artwork using the form above.</div></div>
                    </div>
                @endforelse
            </div>
            {{ $nfts->links() }}
        </div>
    </div>
</div>
<script>
    (function () {
        const input = document.getElementById('nft-image');
        const preview = document.getElementById('nft-image-preview');
        let previewUrl;
        input.addEventListener('change', function () {
            if (previewUrl) URL.revokeObjectURL(previewUrl);
            const file = input.files[0];
            preview.hidden = true;
            preview.removeAttribute('src');
            if (file && ['image/jpeg', 'image/png', 'image/webp', 'image/gif'].includes(file.type)) {
                previewUrl = URL.createObjectURL(file);
                preview.src = previewUrl;
                preview.hidden = false;
            }
        });
    })();
</script>
@endsection
