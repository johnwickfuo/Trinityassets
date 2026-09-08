<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PopupNotification extends Model
{
    protected $fillable = ['admin_id', 'title', 'message', 'action_type', 'expires_at'];

    protected $casts = ['expires_at' => 'datetime'];

    public function recipients()
    {
        return $this->hasMany(PopupNotificationRecipient::class);
    }

    public function scopeActive($query)
    {
        return $query->where('expires_at', '>', now());
    }
}
