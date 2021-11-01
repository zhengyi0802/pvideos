<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'catagory_id',
        'vendor_id',
        'actresses',
        'classifications',
        'keywords',
        'title',
        'thumbnail',
        'video_url',
        'description',
        'publish_date',
        'status',
    ];

}
