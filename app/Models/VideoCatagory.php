<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoCatagory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'parent_id',
        'directory',
        'name',
        'description',
        'status',
    ];
}
