<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

//    protected $casts = [
//        'options' => 'array',
//    ];

    public function options(): BelongsToMany
    {
        return $this->belongsToMany(Options::class, 'apartments_options', 'apartment_id', 'options_id');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }
}
