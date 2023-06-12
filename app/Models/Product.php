<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    //    protected $guarded = [];
    protected $fillable = ['id', 'Product_name', 'description', 'category_id'];

    //relation between products and category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
