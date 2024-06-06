<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostingRequest extends Model
{
    use HasFactory;

    const SUBMITTED = 'Submitted';
    const REJECTED = 'Rejected';
    const APPROVED = 'Approved';

    protected $fillable = ['user_id', 'office_id', 'status', 'remark'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }
}
