<?php

namespace App\Services;

use App\Models\NftConversion;
use App\Models\Tp_Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class NftConversionService
{
    public function payFee(NftConversion $conversion, int $userId): NftConversion
    {
        $result = DB::transaction(function () use ($conversion, $userId) {
            $item = NftConversion::whereKey($conversion->id)->lockForUpdate()->firstOrFail();
            $user = User::whereKey($userId)->lockForUpdate()->firstOrFail();
            $this->ensurePending($item, $userId);
            if ($item->expires_at->isPast()) {
                $this->rejectAndRefund($item, $user, 'The 24-hour payment deadline expired.');
                return null;
            }
            if ((float) $user->account_bal < (float) $item->fee_amount) {
                throw ValidationException::withMessages(['fee' => 'Your main balance is insufficient to pay the conversion fee.']);
            }

            $user->account_bal = round((float) $user->account_bal - (float) $item->fee_amount + (float) $item->converted_amount, 2);
            $user->save();
            $item->update(['status' => 'approved', 'fee_status' => 'paid', 'fee_paid_at' => now(), 'approved_at' => now()]);
            Tp_Transaction::create(['user' => $user->id, 'plan' => $item->reference, 'amount' => -((float) $item->fee_amount), 'type' => 'NFT Conversion Fee']);
            Tp_Transaction::create(['user' => $user->id, 'plan' => $item->reference, 'amount' => $item->converted_amount, 'type' => 'NFT Conversion']);
            return $item->fresh();
        });
        if (!$result) {
            throw ValidationException::withMessages(['conversion' => 'This conversion has expired and the NFT amount was refunded.']);
        }
        return $result;
    }

    public function approveByAdmin(NftConversion $conversion, int $adminId): NftConversion
    {
        $result = DB::transaction(function () use ($conversion, $adminId) {
            $item = NftConversion::whereKey($conversion->id)->lockForUpdate()->firstOrFail();
            $user = User::whereKey($item->user_id)->lockForUpdate()->firstOrFail();
            $this->ensurePending($item, $user->id);
            if ($item->expires_at->isPast()) {
                $this->rejectAndRefund($item, $user, 'The 24-hour payment deadline expired.');
                return null;
            }
            $user->account_bal = round((float) $user->account_bal + (float) $item->converted_amount, 2);
            $user->save();
            $item->update(['status' => 'approved', 'fee_status' => 'waived', 'approved_at' => now(), 'processed_by_admin_id' => $adminId]);
            Tp_Transaction::create(['user' => $user->id, 'plan' => $item->reference, 'amount' => $item->converted_amount, 'type' => 'NFT Conversion']);
            return $item->fresh();
        });
        if (!$result) {
            throw ValidationException::withMessages(['conversion' => 'This conversion has expired and the NFT amount was refunded.']);
        }
        return $result;
    }

    public function rejectByAdmin(NftConversion $conversion, int $adminId, ?string $reason): NftConversion
    {
        return DB::transaction(function () use ($conversion, $adminId, $reason) {
            $item = NftConversion::whereKey($conversion->id)->lockForUpdate()->firstOrFail();
            $user = User::whereKey($item->user_id)->lockForUpdate()->firstOrFail();
            $this->ensurePending($item, $user->id);
            $this->rejectAndRefund($item, $user, $reason ?: 'Rejected by administrator.', $adminId);
            return $item->fresh();
        });
    }

    public function expireOverdue(): int
    {
        $count = 0;
        NftConversion::where('status', 'pending')->where('expires_at', '<=', now())->orderBy('id')->chunkById(100, function ($items) use (&$count) {
            foreach ($items as $conversion) {
                DB::transaction(function () use ($conversion, &$count) {
                    $item = NftConversion::whereKey($conversion->id)->lockForUpdate()->first();
                    if (!$item || $item->status !== 'pending' || $item->expires_at->isFuture()) return;
                    $user = User::whereKey($item->user_id)->lockForUpdate()->first();
                    if ($user) $this->rejectAndRefund($item, $user, 'The 24-hour payment deadline expired.');
                    $count++;
                });
            }
        });
        return $count;
    }

    private function ensurePending(NftConversion $item, int $userId): void
    {
        abort_unless((int) $item->user_id === $userId, 403);
        if ($item->status !== 'pending') throw ValidationException::withMessages(['conversion' => 'This conversion is no longer pending.']);
    }

    private function rejectAndRefund(NftConversion $item, User $user, string $reason, ?int $adminId = null): void
    {
        $user->nft_balance = round((float) $user->nft_balance + (float) $item->nft_amount, 2);
        $user->save();
        $item->update(['status' => 'rejected', 'rejected_at' => now(), 'rejection_reason' => $reason, 'processed_by_admin_id' => $adminId]);
    }
}
