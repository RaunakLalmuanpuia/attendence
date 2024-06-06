<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    const PRESENT = 'present';
    const LATE = 'late';
    const MANUAL = 'manual';

    protected $fillable = ['office_id', 'user_id','device_id', 'signin_at','signout_at','lat','lng','signout_lat','signout_lng','type','in_remark','out_remark'];
    protected $appends = ['formatted_signin','formatted_signout'];

    protected $casts = [
        'signin_at' => 'datetime',
        'signout_at' => 'datetime',
    ];

    const NORMAL_SIGNIN = 'normal';


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }

    public function getFormattedSigninAttribute()
    {
        return $this->signin_at->format('l jS \\of F Y h:i:s A');
    }
    public function getFormattedSignoutAttribute()
    {
        return $this->signout_at?->format('ddd D M-Y');
    }


}
