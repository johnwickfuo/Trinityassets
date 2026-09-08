<?php

namespace App\Actions\Fortify;

use App\Mail\WelcomeEmail;
use App\Models\User;
use App\Models\CryptoAccount;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Illuminate\Support\Facades\Mail;
use App\Support\SupportedCurrencies;
use Illuminate\Validation\Rule;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        $request = request();
        $passwordRules = array_values(array_filter($this->passwordRules(), function ($rule) {
            return $rule !== 'confirmed';
        }));

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:191', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'currency_code' => ['required', Rule::in(array_keys(SupportedCurrencies::all()))],
            'password' => $passwordRules,
        ])->validate();

        $currencyCode = $input['currency_code'];
        $currency = SupportedCurrencies::get($currencyCode);

        $referrer = session('ref_by') ?: ($input['ref_by'] ?? null);
        $ref_by_id = $referrer ? User::where('username', $referrer)->value('id') : null;

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'username' => $input['username'],
            'ref_by' => $ref_by_id,
            'status' => 'active',
            'currency' => $currency['symbol'],
            's_currency' => $currencyCode,
            'password' => Hash::make($input['password']),
        ]);

        $cryptoaccnt = new CryptoAccount();
        $cryptoaccnt->user_id = $user->id;
        $cryptoaccnt->save();
        $request->session()->forget('ref_by');

        try {
            Mail::to($user->email)->send(new WelcomeEmail($user));
        } catch (\Exception $e) {
            \Log::error('Failed to send welcome email to user: ' . $user->email . '. Error: ' . $e->getMessage());
        }

        return $user;
    }
}
