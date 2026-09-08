<?php

namespace App\Support;

class SupportedCurrencies
{
    public static function all(): array
    {
        return [
            'TTD' => ['name' => 'Trinidad and Tobago Dollar', 'symbol' => 'TT$'],
            'USD' => ['name' => 'US Dollar', 'symbol' => '$'],
            'EUR' => ['name' => 'Euro', 'symbol' => '€'],
            'GBP' => ['name' => 'British Pound', 'symbol' => '£'],
            'CAD' => ['name' => 'Canadian Dollar', 'symbol' => 'C$'],
            'AUD' => ['name' => 'Australian Dollar', 'symbol' => 'A$'],
            'NGN' => ['name' => 'Nigerian Naira', 'symbol' => '₦'],
            'BBD' => ['name' => 'Barbadian Dollar', 'symbol' => 'Bds$'],
            'JMD' => ['name' => 'Jamaican Dollar', 'symbol' => 'J$'],
            'GYD' => ['name' => 'Guyanese Dollar', 'symbol' => 'G$'],
            'XCD' => ['name' => 'East Caribbean Dollar', 'symbol' => 'EC$'],
            'BSD' => ['name' => 'Bahamian Dollar', 'symbol' => 'B$'],
            'BZD' => ['name' => 'Belize Dollar', 'symbol' => 'BZ$'],
            'ZAR' => ['name' => 'South African Rand', 'symbol' => 'R'],
            'BWP' => ['name' => 'Botswana Pula', 'symbol' => 'P'],
            'GHS' => ['name' => 'Ghanaian Cedi', 'symbol' => 'GH₵'],
            'KES' => ['name' => 'Kenyan Shilling', 'symbol' => 'KSh'],
            'AED' => ['name' => 'UAE Dirham', 'symbol' => 'د.إ'],
            'INR' => ['name' => 'Indian Rupee', 'symbol' => '₹'],
            'JPY' => ['name' => 'Japanese Yen', 'symbol' => '¥'],
            'CNY' => ['name' => 'Chinese Yuan', 'symbol' => 'CN¥'],
            'CHF' => ['name' => 'Swiss Franc', 'symbol' => 'CHF'],
            'NZD' => ['name' => 'New Zealand Dollar', 'symbol' => 'NZ$'],
        ];
    }

    public static function get(string $code): ?array
    {
        return static::all()[$code] ?? null;
    }
}
