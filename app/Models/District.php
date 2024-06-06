<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class District extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name'];

    public function offices(): HasMany
    {
        return $this->hasMany(Office::class);
    }

    public function attendances(): HasManyThrough
    {
        return $this->hasManyThrough(Attendance::class, Office::class);
    }

    public function users(): HasManyThrough
    {
        return $this->hasManyThrough(User::class, Office::class);
    }
}
