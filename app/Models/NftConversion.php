<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NftConversion extends Model
{
    protected $fillable = [
        'user_id', 'reference', 'nft_amount', 'exchange_rate', 'converted_amount',
        'fee_percentage', 'fee_amount', 'from_currency', 'to_currency', 'status',
        'fee_status', 'expires_at', 'fee_paid_at', 'approved_at', 'rejected_at',
        'processed_by_admin_id', 'rejection_reason',
    ];

    protected $casts = [
        'nft_amount' => 'decimal:2', 'exchange_rate' => 'decimal:4',
        'converted_amount' => 'decimal:2', 'fee_percentage' => 'decimal:2',
        'fee_amount' => 'decimal:2', 'expires_at' => 'datetime',
        'fee_paid_at' => 'datetime', 'approved_at' => 'datetime', 'rejected_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
