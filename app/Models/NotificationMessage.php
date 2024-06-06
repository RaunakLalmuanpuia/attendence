<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Kreait\Laravel\Firebase\Facades\Firebase;

class NotificationMessage extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'url','body', 'schedule_at','office_id'];

    protected $casts = [
        'schedule_at' => 'datetime',
    ];

    public function office(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Office::class);
    }
    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class);
    }

    public static function test()
    {
        Firebase::messaging();
    }
}
