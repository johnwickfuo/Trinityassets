<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\NftConversionInitiatedMail;
use App\Models\NftConversion;
use App\Models\User;
use App\Services\NftConversionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class NftConversionController extends Controller
{
    public function index(NftConversionService $service)
    {
        $service->expireOverdue();
        return view('user.nfts.conversions.index', [
            'title' => 'Swap NFT for Cash',
            'conversions' => NftConversion::where('user_id', Auth::id())->latest('id')->paginate(10),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate(['amount' => ['required', 'numeric', 'min:1', 'max:999999999999.99', 'regex:/^\d{1,12}(?:\.\d{1,2})?$/']]);
        $result = DB::transaction(function () use ($data) {
            $user = User::whereKey(Auth::id())->lockForUpdate()->firstOrFail();
            $amount = round((float) $data['amount'], 2);
            if ((float) $user->nft_balance < $amount) throw ValidationException::withMessages(['amount' => 'Your NFT balance is insufficient.']);
            $convertedAmount = round($amount * 10, 2);
            $user->nft_balance = round((float) $user->nft_balance - $amount, 2);
            $user->save();
            $conversion = NftConversion::create([
                'user_id' => $user->id, 'reference' => 'NFTC-'.strtoupper(Str::random(12)),
                'nft_amount' => $amount, 'exchange_rate' => 10, 'converted_amount' => $convertedAmount,
                'fee_percentage' => 10, 'fee_amount' => round($convertedAmount * 0.10, 2),
                'from_currency' => 'USDT', 'to_currency' => 'TTD', 'status' => 'pending',
                'fee_status' => 'unpaid', 'expires_at' => now()->addHours(24),
            ]);
            return ['user' => $user, 'conversion' => $conversion];
        });

        try {
            Mail::to($result['user']->email)->send(new NftConversionInitiatedMail($result['user'], $result['conversion']));
        } catch (\Throwable $exception) {
            Log::error('Failed to send NFT conversion email.', ['user_id' => $result['user']->id, 'conversion_id' => $result['conversion']->id, 'error' => $exception->getMessage()]);
        }
        return redirect()->route('user.nfts.conversions.show', $result['conversion'])->with('success', 'Conversion initiated. Pay the fee within 24 hours to complete it.');
    }

    public function show(NftConversion $conversion, NftConversionService $service)
    {
        abort_unless((int) $conversion->user_id === (int) Auth::id(), 404);
        $service->expireOverdue();
        return view('user.nfts.conversions.show', ['title' => 'Conversion '.$conversion->reference, 'conversion' => $conversion->fresh()]);
    }

    public function payFee(NftConversion $conversion, NftConversionService $service)
    {
        $service->payFee($conversion, Auth::id());
        return redirect()->route('user.nfts.conversions.show', $conversion)->with('success', 'Fee paid. Your conversion has been approved and credited to your main balance.');
    }
}
