<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PopupNotificationRecipient extends Model
{
    protected $fillable = ['popup_notification_id', 'user_id', 'dismissed_at'];

    protected $casts = ['dismissed_at' => 'datetime'];

    public function notification()
    {
        return $this->belongsTo(PopupNotification::class, 'popup_notification_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
