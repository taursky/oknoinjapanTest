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
        'id',
        'owner_name',
        'city_id',
        'address',
        'bedrooms',
        'house_floors',
        'floor',
        'price',
    ];

    public function options(): BelongsToMany
    {
        return $this->belongsToMany(Options::class, 'apartments_options', 'apartment_id', 'options_id');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }
}
