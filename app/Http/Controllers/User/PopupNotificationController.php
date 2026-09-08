<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PopupNotificationRecipient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PopupNotificationController extends Controller
{
    public function dismiss(Request $request, PopupNotificationRecipient $recipient)
    {
        abort_unless((int) $recipient->user_id === (int) Auth::id(), 403);
        if (!$recipient->dismissed_at) {
            $recipient->update(['dismissed_at' => now()]);
        }

        return $request->expectsJson()
            ? response()->json(['dismissed' => true])
            : back();
    }
}
