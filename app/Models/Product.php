<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'status_id'];

    /**
     * Get the product references associated with this product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function references(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProductReference::class, 'product_id');
    }

    /**
     * Get the product images associated with this product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    /**
     * Define the "status" relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Status::class, 'status_id');
    }
}
