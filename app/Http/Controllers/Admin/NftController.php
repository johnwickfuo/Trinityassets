<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Nft;
use App\Models\Settings;
use App\Services\NftBidService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class NftController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $admin = Auth::guard('admin')->user();
            abort_unless($admin && in_array($admin->type, ['Super Admin', 'Admin'], true), 403);

            return $next($request);
        });
    }

    public function create()
    {
        return view('admin.nfts.create', [
            'title' => 'Upload NFT',
            'nfts' => Nft::with(['owner', 'currentBid'])->orderByDesc('id')->paginate(12),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:2000'],
            'price' => ['required', 'numeric', 'min:0.01', 'max:9999999999.99', 'regex:/^\d{1,10}(?:\.\d{1,2})?$/'],
            'projected_min_value' => ['required', 'numeric', 'gte:price', 'max:9999999999.99', 'regex:/^\d{1,10}(?:\.\d{1,2})?$/'],
            'projected_max_value' => ['required', 'numeric', 'gte:projected_min_value', 'max:9999999999.99', 'regex:/^\d{1,10}(?:\.\d{1,2})?$/'],
            'auto_bid_enabled' => ['nullable', 'boolean'],
            'image' => ['required', 'file', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:4096', 'dimensions:max_width=6000,max_height=6000'],
        ], [
            'price.regex' => 'Enter a price with no more than two decimal places.',
            'projected_min_value.gte' => 'The minimum projected value must be at least the purchase price.',
            'projected_max_value.gte' => 'The maximum projected value must be at least the minimum projected value.',
            'image.max' => 'The NFT image must be no larger than 4 MB.',
        ]);

        $settings = Settings::findOrFail(1);
        // Snapshot the site's currency; never relabel existing prices after a settings change.
        $currency = $settings->s_currency ?: $settings->currency;
        abort_unless(is_string($currency) && trim($currency) !== '' && mb_strlen($currency) <= 16, 422, 'Set a valid site currency before uploading an NFT.');

        $path = $request->file('image')->store('nfts', 'local');
        if (!$path) {
            return back()->withInput($request->except('image'))
                ->withErrors(['image' => 'The image could not be saved. Please try again.']);
        }

        try {
            Nft::create([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'price' => $data['price'],
                'projected_min_value' => $data['projected_min_value'],
                'projected_max_value' => $data['projected_max_value'],
                'currency' => trim($currency),
                'image_path' => $path,
                'is_available' => true,
                'auto_bid_enabled' => $request->boolean('auto_bid_enabled'),
            ]);
        } catch (\Throwable $exception) {
            // Do not leave an orphan image when the database write fails.
            Storage::disk('local')->delete($path);
            throw $exception;
        }

        return redirect()->route('admin.nfts.create')
            ->with('success', 'NFT uploaded. It is now visible in the Buy NFT catalogue.');
    }

    public function manualBid(Request $request, Nft $nft, NftBidService $bidService)
    {
        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01', 'max:9999999999.99', 'regex:/^\d{1,10}(?:\.\d{1,2})?$/'],
        ]);
        $bidService->createManual($nft, $data['amount']);

        return back()->with('success', 'Manual bid created for '.$nft->name.'.');
    }

    public function automaticBid(Nft $nft, NftBidService $bidService)
    {
        $bid = $bidService->createAutomatic($nft);

        return back()->with('success', 'Automatic bid of '.$bid->currency.' '.number_format($bid->amount, 2).' generated.');
    }

    public function toggleAutomaticBids(Request $request, Nft $nft)
    {
        $data = $request->validate(['enabled' => ['required', 'boolean']]);
        $nft->update(['auto_bid_enabled' => (bool) $data['enabled']]);

        return back()->with('success', 'Automatic daily bids updated for '.$nft->name.'.');
    }

    public function image(Nft $nft)
    {
        abort_unless(Storage::disk('local')->exists($nft->image_path), 404);

        return Storage::disk('local')->response($nft->image_path, null, [
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'private, max-age=3600',
        ]);
    }
}
