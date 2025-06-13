<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'id'
    ];
    public function apartments(): HasMany
    {
        return $this->hasMany(Apartment::class,'city_id');
    }
}
