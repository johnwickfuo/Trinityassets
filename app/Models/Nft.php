<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nft extends Model
{
    protected $fillable = [
        'name', 'description', 'image_path', 'price', 'projected_min_value',
        'projected_max_value', 'currency', 'is_available', 'owner_user_id',
        'purchase_price', 'purchased_at', 'auto_bid_enabled',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'projected_min_value' => 'decimal:2',
        'projected_max_value' => 'decimal:2',
        'purchase_price' => 'decimal:2',
        'is_available' => 'boolean',
        'auto_bid_enabled' => 'boolean',
        'purchased_at' => 'datetime',
    ];

    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function bids()
    {
        return $this->hasMany(NftBid::class);
    }

    public function currentBid()
    {
        return $this->hasOne(NftBid::class)->where('status', 'active')->latestOfMany();
    }
}
