<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NftTransaction extends Model
{
    protected $fillable = ['user_id', 'nft_id', 'nft_bid_id', 'type', 'amount', 'currency', 'nft_balance_after'];

    protected $casts = [
        'amount' => 'decimal:2',
        'nft_balance_after' => 'decimal:2',
    ];
}
