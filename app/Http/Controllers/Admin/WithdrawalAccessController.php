<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WithdrawalRestriction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WithdrawalAccessController extends Controller
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
        return view('admin.withdrawal-access.index', [
            'title' => 'Withdrawal Access',
            'users' => User::select('id', 'name', 'email')->orderBy('name')->get(),
            'blockedUsers' => WithdrawalRestriction::with('user:id,name,email')
                ->where('is_blocked', true)
                ->latest('blocked_at')
                ->paginate(15),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'action' => ['required', 'in:block,enable'],
            'message' => ['nullable', 'required_if:action,block', 'string', 'max:2000'],
        ]);

        $user = User::select('id', 'name')->findOrFail($data['user_id']);
        $isBlocking = $data['action'] === 'block';

        DB::transaction(function () use ($data, $isBlocking) {
            $restriction = WithdrawalRestriction::where('user_id', $data['user_id'])
                ->lockForUpdate()
                ->first();

            if (!$restriction) {
                $restriction = new WithdrawalRestriction(['user_id' => $data['user_id']]);
            }

            $restriction->fill([
                'is_blocked' => $isBlocking,
                'message' => $isBlocking ? trim($data['message']) : null,
                'updated_by_admin_id' => Auth::guard('admin')->id(),
                'blocked_at' => $isBlocking ? now() : $restriction->blocked_at,
                'unblocked_at' => $isBlocking ? null : now(),
            ])->save();
        });

        $status = $isBlocking ? 'disabled' : 'enabled';

        return redirect()->route('admin.withdrawal-access.index')
            ->with('success', "Withdrawals have been {$status} for {$user->name}.");
    }
}
