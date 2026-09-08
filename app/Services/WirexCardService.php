<?php

namespace App\Services;

use App\Models\Deposit;
use App\Models\WirexCardOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class WirexCardService
{
    public function activateFromDeposit(Deposit $deposit): WirexCardOrder
    {
        return DB::transaction(function () use ($deposit) {
            $item = Deposit::whereKey($deposit->id)->lockForUpdate()->firstOrFail();
            $order = WirexCardOrder::where('deposit_id', $item->id)->lockForUpdate()->firstOrFail();

            if ($order->status === 'active') return $order;
            if ($item->purpose !== 'wirex_card' || $item->purpose_reference !== $order->reference || (int) $item->user !== (int) $order->user_id) {
                throw ValidationException::withMessages(['deposit' => 'This deposit is not linked to the Wirex Card order.']);
            }
            if (round((float) $item->amount, 2) !== round((float) $order->total_fee, 2)) {
                throw ValidationException::withMessages(['deposit' => 'The Wirex Card payment amount is incorrect.']);
            }

            do {
                $code = 'WXC-'.strtoupper(Str::random(12));
            } while (WirexCardOrder::where('card_code', $code)->exists());

            $item->update(['status' => 'Processed']);
            $order->update(['status' => 'active', 'card_code' => $code, 'activated_at' => now()]);
            return $order->fresh();
        });
    }
}
