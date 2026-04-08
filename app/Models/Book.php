<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'title',
        'author',
        'description',
        'price',
        'cover_image',
        'stock',
        'category'
    ];
    
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
    
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }
    
    public function getAverageRatingAttribute()
    {
        return $this->ratings()->avg('rating') ?? 0;
    }
    
    public function getTotalRatingsAttribute()
    {
        return $this->ratings()->count();
    }
    
    public function getUserRatingAttribute()
    {
        if (auth()->check()) {
            return $this->ratings()->where('user_id', auth()->id())->first()->rating ?? null;
        }
        return null;
    }
}