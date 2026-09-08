<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nft extends Model
{
    protected $fillable = ['name', 'description', 'image_path', 'price', 'currency', 'is_available'];

    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean',
    ];

    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }
}
