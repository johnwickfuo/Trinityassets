<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Nft;
use App\Models\NftBid;
use App\Models\NftTransaction;
use App\Models\Tp_Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class NftController extends Controller
{
    public function index()
    {
        return view('user.nfts.index', [
            'title' => 'Buy NFT',
            'nfts' => Nft::available()->whereNull('owner_user_id')->orderByDesc('id')->paginate(12),
        ]);
    }

    public function collection()
    {
        return view('user.nfts.collection', [
            'title' => 'View My NFTs',
            'nfts' => Nft::where('owner_user_id', Auth::id())->with('currentBid')->latest('purchased_at')->paginate(12),
        ]);
    }

    public function show(Nft $nft)
    {
        abort_unless((int) $nft->owner_user_id === (int) Auth::id(), 404);
        $nft->load(['bids' => function ($query) {
            $query->latest('id');
        }, 'currentBid']);

        return view('user.nfts.show', ['title' => $nft->name, 'nft' => $nft]);
    }

    public function buy(Nft $nft)
    {
        DB::transaction(function () use ($nft) {
            $lockedNft = Nft::whereKey($nft->id)->lockForUpdate()->firstOrFail();
            $user = User::whereKey(Auth::id())->lockForUpdate()->firstOrFail();
            if (!$lockedNft->is_available || $lockedNft->owner_user_id) {
                throw ValidationException::withMessages(['nft' => 'This NFT is no longer available.']);
            }
            if ((float) $user->account_bal < (float) $lockedNft->price) {
                throw ValidationException::withMessages(['nft' => 'Your account balance is insufficient for this NFT.']);
            }

            $user->account_bal = round((float) $user->account_bal - (float) $lockedNft->price, 2);
            $user->save();
            $lockedNft->update([
                'owner_user_id' => $user->id,
                'is_available' => false,
                'purchase_price' => $lockedNft->price,
                'purchased_at' => now(),
            ]);
            NftTransaction::create([
                'user_id' => $user->id, 'nft_id' => $lockedNft->id, 'type' => 'purchase',
                'amount' => $lockedNft->price, 'currency' => $lockedNft->currency,
                'nft_balance_after' => $user->nft_balance,
            ]);
            Tp_Transaction::create(['user' => $user->id, 'amount' => -((float) $lockedNft->price), 'type' => 'NFT Purchase']);
        });

        return redirect()->route('user.nfts.collection')->with('success', 'NFT purchased and added to your collection.');
    }

    public function sell(Request $request, Nft $nft)
    {
        $data = $request->validate(['bid_id' => ['required', 'integer']]);
        DB::transaction(function () use ($nft, $data) {
            $lockedNft = Nft::whereKey($nft->id)->lockForUpdate()->firstOrFail();
            $user = User::whereKey(Auth::id())->lockForUpdate()->firstOrFail();
            abort_unless((int) $lockedNft->owner_user_id === (int) $user->id, 403);
            $bid = NftBid::whereKey($data['bid_id'])->where('nft_id', $lockedNft->id)->lockForUpdate()->firstOrFail();
            if ($bid->status !== 'active') {
                throw ValidationException::withMessages(['bid' => 'This bid is no longer active.']);
            }

            $user->nft_balance = round((float) $user->nft_balance + (float) $bid->amount, 2);
            $user->save();
            $bid->update(['status' => 'accepted']);
            $lockedNft->update([
                'owner_user_id' => null, 'is_available' => true, 'price' => $bid->amount,
                'purchase_price' => null, 'purchased_at' => null,
            ]);
            NftTransaction::create([
                'user_id' => $user->id, 'nft_id' => $lockedNft->id, 'nft_bid_id' => $bid->id,
                'type' => 'sale', 'amount' => $bid->amount, 'currency' => $bid->currency,
                'nft_balance_after' => $user->nft_balance,
            ]);
        });

        return redirect()->route('user.nfts.collection')->with('success', 'NFT sold. The bid amount has been credited to your NFT balance.');
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
        abort_unless(($nft->is_available || (int) $nft->owner_user_id === (int) Auth::id()) && Storage::disk('local')->exists($nft->image_path), 404);

        return Storage::disk('local')->response($nft->image_path, null, [
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'private, max-age=3600',
        ]);
    }
}
