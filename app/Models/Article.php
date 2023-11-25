<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
    protected $guarded=['id'];
    protected $with=['country','category'];
    protected $appends=['country_name','category_name'];
    public function getCountryNameAttribute()
    {
        return $this->country?->name;
    }
    public function getCategoryNameAttribute()
    {
        return $this->category?->name;
    }
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
