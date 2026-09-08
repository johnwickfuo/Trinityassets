<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NftBid extends Model
{
    protected $fillable = ['nft_id', 'amount', 'currency', 'source', 'status'];

    protected $casts = ['amount' => 'decimal:2'];

    public function nft()
    {
        return $this->belongsTo(Nft::class);
    }
}
