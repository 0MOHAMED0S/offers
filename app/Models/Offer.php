<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $fillable = [
        'store_id',
        'name',
        'description',
        'price_before',
        'price_after',
        'status',
        'image',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    public function favoritedBy()
{
    return $this->belongsToMany(User::class, 'favorites');
}

}
