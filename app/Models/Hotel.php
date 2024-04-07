<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'description',
        'address',
        'city',
        'country',
        'zip_code',
        'url_location',
        'star_rating',
        'image_logo',
        'facebook',
        'instagram',
        'youtube',
        'twitter',
        'telegram'
    ];

}
