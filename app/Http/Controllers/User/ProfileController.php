<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Support\SupportedCurrencies;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    //Updating Profile Route
    public function updateprofile(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'dob' => ['nullable', 'date'],
            'phone' => ['required', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:1000'],
            'country' => ['required', 'string', 'max:191'],
            'currency_code' => ['required', Rule::in(array_keys(SupportedCurrencies::all()))],
        ]);
        $currency = SupportedCurrencies::get($data['currency_code']);
        User::where('id', Auth::user()->id)
            ->update([
                'name' => $data['name'],
                'dob' => $data['dob'] ?? null,
                'phone' => $data['phone'],
                'address' => $data['address'] ?? null,
                'country' => $data['country'],
                'currency' => $currency['symbol'],
                's_currency' => $data['currency_code'],
            ]);
        if ($request->expectsJson()) {
            return response()->json(['status' => 200, 'success' => 'Profile information updated successfully.']);
        }

        return redirect()->back()->with('success', 'Profile information updated successfully.');
    }

    //update account and contact info
    public function updateacct(Request $request)
    {
        User::where('id', Auth::user()->id)
            ->update([
                'bank_name' => $request['bank_name'],
                'account_name' => $request['account_name'],
                'account_number' => $request['account_no'],
                'swift_code' => $request['swiftcode'],
                'btc_address' => $request['btc_address'],
                'eth_address' => $request['eth_address'],
                'ltc_address' => $request['ltc_address'],
                'usdt_address' => $request['usdt_address'],
            ]);
        return response()->json(['status' => 200, 'success' => 'Withdrawal Info updated Sucessfully']);
    }

    //Update Password
    public function updatepass(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required',
        ]);

        $user = User::find(Auth::user()->id);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('message', 'Current password does not match!');
        }
        $user->password = Hash::make($request->password);
        $user->save();
        return back()->with('success', 'Password updated successfully');
    }

    // Update email preference logic
    public function updateemail(Request $request)
    {
        $user = User::find(Auth::user()->id);

        $user->sendotpemail = $request->otpsend;
        $user->sendroiemail = $request->roiemail;
        $user->sendinvplanemail = $request->invplanemail;
        $user->save();
        return response()->json(['status' => 200, 'success' => 'Email Preference updated']);
    }
}
