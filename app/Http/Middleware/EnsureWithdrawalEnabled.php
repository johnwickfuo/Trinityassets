<?php

namespace App\Http\Middleware;

use App\Models\WithdrawalRestriction;
use Closure;
use Illuminate\Support\Facades\Auth;

class EnsureWithdrawalEnabled
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            return $next($request);
        }

        $restriction = WithdrawalRestriction::where('user_id', Auth::id())
            ->where('is_blocked', true)
            ->first();

        if (!$restriction) {
            return $next($request);
        }

        return redirect()->route('dashboard')->with(
            'withdrawal_blocked_message',
            $restriction->message ?: 'Withdrawals are temporarily unavailable for your account. Please contact support.'
        );
    }
}
