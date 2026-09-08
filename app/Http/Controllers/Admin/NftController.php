<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Nft;
use App\Models\Settings;
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
            'nfts' => Nft::orderByDesc('id')->paginate(12),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:2000'],
            'price' => ['required', 'numeric', 'min:0.01', 'max:9999999999.99', 'regex:/^\d{1,10}(?:\.\d{1,2})?$/'],
            'image' => ['required', 'file', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:4096', 'dimensions:max_width=6000,max_height=6000'],
        ], [
            'price.regex' => 'Enter a price with no more than two decimal places.',
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
                'currency' => trim($currency),
                'image_path' => $path,
                'is_available' => true,
            ]);
        } catch (\Throwable $exception) {
            // Do not leave an orphan image when the database write fails.
            Storage::disk('local')->delete($path);
            throw $exception;
        }

        return redirect()->route('admin.nfts.create')
            ->with('success', 'NFT uploaded. It is now visible in the Buy NFT catalogue.');
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
