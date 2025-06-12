<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Apartment extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_name',
        'city',
        'address',
        'bedrooms',
        'house_floors',
        'floor',
        'options',
        'price',
    ];

    protected $casts = [
        'options' => 'array',
    ];
}
