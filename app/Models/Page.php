<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    const TERM = 'term';
    const PRIVACY = 'privacy';
    protected $fillable = ['title', 'content', 'type'];
}
