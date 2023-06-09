<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductReference extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'reference', 'price', 'stock', 'status_id'];

    /**
     * Define the "status" relationship.
     *
     * @return BelongsTo
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'status_id');
    }
}
