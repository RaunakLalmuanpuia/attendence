<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Device extends Model
{
    use HasFactory;

    const SUBMITTED = 'Submitted';
    const APPROVED = 'Approved';
    const REJECTED = 'Rejected';
    protected $fillable = ['name', 'uid','active','user_id','status'];

    protected $casts = [
        'active' => 'boolean'
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


}
