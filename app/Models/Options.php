<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Options extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
    ];
    public function apartments(): BelongsToMany
    {
        return $this->belongsToMany(Apartment::class, 'apartments_options', 'options_id', 'apartment_id');
    }

    protected $table = 'options';
}
