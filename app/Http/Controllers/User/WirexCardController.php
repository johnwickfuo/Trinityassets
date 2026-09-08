<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\WirexCardOrder;
use App\Support\SupportedCurrencies;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WirexCardController extends Controller
{
    public function index()
    {
        return view('user.wirex-card', [
            'title' => 'Wirex Card',
            'order' => WirexCardOrder::where('user_id', Auth::id())->first(),
        ]);
    }

    public function purchase()
    {
        $order = DB::transaction(function () {
            $user = Auth::user();
            $currencyCode = SupportedCurrencies::get($user->s_currency ?: '') ? $user->s_currency : 'TTD';
            $currency = SupportedCurrencies::get($currencyCode);
            $order = WirexCardOrder::where('user_id', $user->id)->lockForUpdate()->first();

            if ($order && $order->status === 'active') {
                return $order;
            }
            if ($order && $order->status === 'payment_pending' && $order->deposit_id) {
                return $order;
            }

            $values = [
                'reference' => $order ? $order->reference : 'WXC-'.strtoupper(Str::random(12)),
                'currency_code' => $currencyCode,
                'currency_symbol' => $currency['symbol'],
                'card_fee' => 1700,
                'activation_fee' => 1700,
                'total_fee' => 3400,
                'status' => 'awaiting_payment',
            ];

            if ($order) {
                $order->update($values);
                return $order->fresh();
            }

            return WirexCardOrder::create(['user_id' => $user->id] + $values);
        });

        if ($order->status === 'active') {
            return redirect()->route('user.wirex-card.index')->with('success', 'Your Wirex Card is already active.');
        }
        if ($order->status === 'payment_pending') {
            return redirect()->route('user.wirex-card.index')->with('success', 'Your card payment is already awaiting confirmation.');
        }

        return redirect()->route('deposits', ['wirex_order' => $order->id]);
    }
}
