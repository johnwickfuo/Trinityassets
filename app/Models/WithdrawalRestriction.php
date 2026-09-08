<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WithdrawalRestriction extends Model
{
    protected $fillable = [
        'user_id',
        'is_blocked',
        'message',
        'updated_by_admin_id',
        'blocked_at',
        'unblocked_at',
    ];

    protected $casts = [
        'is_blocked' => 'boolean',
        'blocked_at' => 'datetime',
        'unblocked_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
