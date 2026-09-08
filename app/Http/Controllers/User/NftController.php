<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Nft;
use Illuminate\Support\Facades\Storage;

class NftController extends Controller
{
    public function index()
    {
        return view('user.nfts.index', [
            'title' => 'Buy NFT',
            'nfts' => Nft::available()->orderByDesc('id')->paginate(12),
        ]);
    }

    public function collection()
    {
        return view('user.nfts.coming-soon', [
            'title' => 'View My NFTs',
            'message' => 'Your NFT collection page is coming soon. You can explore the available artwork in the meantime.',
        ]);
    }

    public function swap()
    {
        return view('user.nfts.coming-soon', [
            'title' => 'Swap NFT for Cash',
            'message' => 'NFT cash swaps are coming soon. You can explore the available artwork in the meantime.',
        ]);
    }

    public function image(Nft $nft)
    {
        abort_unless($nft->is_available && Storage::disk('local')->exists($nft->image_path), 404);

        return Storage::disk('local')->response($nft->image_path, null, [
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'private, max-age=3600',
        ]);
    }
}
