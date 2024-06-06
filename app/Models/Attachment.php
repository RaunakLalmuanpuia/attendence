<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'path','notification_message_id'];
    protected $appends = ['full_path'];
    public function notificationMessage(): BelongsTo
    {
        return $this->belongsTo(NotificationMessage::class);
    }

    public function getFullPathAttribute(): string
    {
        return env('APP_URL').'/storage/'.$this->path;
    }

}
