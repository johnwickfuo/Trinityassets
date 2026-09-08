<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WirexCardOrder extends Model
{
    protected $fillable = [
        'user_id', 'deposit_id', 'reference', 'card_code', 'currency_code',
        'currency_symbol', 'card_fee', 'activation_fee', 'total_fee', 'status',
        'activated_at',
    ];

    protected $casts = [
        'card_fee' => 'decimal:2', 'activation_fee' => 'decimal:2',
        'total_fee' => 'decimal:2', 'activated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function deposit()
    {
        return $this->belongsTo(Deposit::class);
    }
}
