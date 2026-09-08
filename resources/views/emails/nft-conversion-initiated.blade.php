@component('mail::message')
# Dear {{ $user->name }},

Your conversion is currently pending while your NFT balance is converted from {{ $conversion->from_currency }} to {{ $conversion->to_currency }} for credit to your main account balance.

To complete the conversion, a 10% currency conversion and processing fee must be paid from your main account balance within 24 hours.

## Conversion Summary

**Reference:** {{ $conversion->reference }}  
**NFT amount:** {{ $conversion->from_currency }} {{ number_format($conversion->nft_amount, 2) }}  
**Exchange rate:** {{ rtrim(rtrim(number_format($conversion->exchange_rate, 4), '0'), '.') }} {{ $conversion->to_currency }} = 1 {{ $conversion->from_currency }}  
**Amount to receive:** {{ $conversion->to_currency }} {{ number_format($conversion->converted_amount, 2) }}  
**Required conversion fee:** {{ $conversion->to_currency }} {{ number_format($conversion->fee_amount, 2) }}  
**Payment method:** Main account balance  
**Deadline:** {{ $conversion->expires_at->format('M d, Y h:i A') }}

If your main balance is insufficient, deposit funds before paying the fee. Once the fee is paid, the conversion is approved automatically and the converted amount is credited to your main balance.

@component('mail::button', ['url' => $detailsUrl])
View Conversion and Pay Fee
@endcomponent

Please contact support if you require assistance.

Kind regards,  
The {{ $siteName }} Team
@endcomponent
