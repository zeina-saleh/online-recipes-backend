<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Plan extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'recipe_id',
        'date',
    ];
    
    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }
}
