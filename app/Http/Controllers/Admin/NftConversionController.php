<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NftConversion;
use App\Services\NftConversionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NftConversionController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $admin = Auth::guard('admin')->user();
            abort_unless($admin && in_array($admin->type, ['Super Admin', 'Admin'], true), 403);
            return $next($request);
        });
    }

    public function index(Request $request, NftConversionService $service)
    {
        $service->expireOverdue();
        $query = NftConversion::with('user')->latest('id');
        if (in_array($request->status, ['pending', 'approved', 'rejected'], true)) $query->where('status', $request->status);
        return view('admin.nft-conversions.index', ['title' => 'NFT Conversions', 'conversions' => $query->paginate(15)->withQueryString(), 'status' => $request->status]);
    }

    public function approve(NftConversion $conversion, NftConversionService $service)
    {
        $service->approveByAdmin($conversion, Auth::guard('admin')->id());
        return back()->with('success', 'Conversion approved and the user’s main balance was credited.');
    }

    public function reject(Request $request, NftConversion $conversion, NftConversionService $service)
    {
        $data = $request->validate(['reason' => ['nullable', 'string', 'max:1000']]);
        $service->rejectByAdmin($conversion, Auth::guard('admin')->id(), $data['reason'] ?? null);
        return back()->with('success', 'Conversion rejected and the reserved NFT balance was refunded.');
    }
}
