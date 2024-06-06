<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,HasRoles,SoftDeletes;

    protected $fillable = [
        'employee_no',
        'full_name',
        'designation',
        'mobile',
        'password',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function fcmTokens(): HasMany
    {
        return $this->hasMany(FcmToken::class);
    }
    public function office(): HasOneThrough
    {
        return $this->hasOneThrough(Office::class,UserOffice::class,'user_id','office_id','id','id');
    }
    public function offices(): BelongsToMany
    {
        return $this->belongsToMany(Office::class, 'user_offices');
    }

    public function devices(): HasMany
    {
        return $this->hasMany(Device::class);
    }


    public function attendance(): HasOne
    {
        return $this->hasOne(Attendance::class)->latestOfMany();
    }
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function postingRequests(): HasMany
    {
        return $this->hasMany(PostingRequest::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }
}
