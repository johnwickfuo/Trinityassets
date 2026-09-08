<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PopupNotification;
use App\Models\PopupNotificationRecipient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PopupNotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $admin = Auth::guard('admin')->user();
            abort_unless($admin && in_array($admin->type, ['Super Admin', 'Admin'], true), 403);
            return $next($request);
        });
    }

    public function index()
    {
        return view('admin.popup-notifications.index', [
            'title' => 'Send Notification',
            'users' => User::select('id', 'name', 'email')->orderBy('name')->get(),
            'notifications' => PopupNotification::withCount('recipients')->latest('id')->paginate(10),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_ids' => ['required', 'array', 'min:1'],
            'user_ids.*' => ['required', 'integer', 'distinct', 'exists:users,id'],
            'title' => ['required', 'string', 'max:150'],
            'message' => ['required', 'string', 'max:5000'],
            'duration_days' => ['required', 'integer', 'min:1', 'max:365'],
            'action_type' => ['nullable', 'in:deposit,buy_nft,my_nfts'],
        ]);

        DB::transaction(function () use ($data) {
            $notification = PopupNotification::create([
                'admin_id' => Auth::guard('admin')->id(),
                'title' => $data['title'],
                'message' => $data['message'],
                'action_type' => $data['action_type'] ?? null,
                'expires_at' => now()->addDays((int) $data['duration_days']),
            ]);

            $now = now();
            $rows = collect($data['user_ids'])->map(function ($userId) use ($notification, $now) {
                return [
                    'popup_notification_id' => $notification->id,
                    'user_id' => $userId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            })->all();
            PopupNotificationRecipient::insert($rows);
        });

        return redirect()->route('admin.popup-notifications.index')
            ->with('success', 'Popup notification sent to '.count($data['user_ids']).' user(s).');
    }
}
