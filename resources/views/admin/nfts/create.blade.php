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
                                <span class="badge {{ $nft->is_available ? 'badge-success' : 'badge-secondary' }}">{{ $nft->is_available ? 'Available' : 'Unavailable' }}</span>
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
