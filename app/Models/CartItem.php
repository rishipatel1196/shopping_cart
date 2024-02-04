<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CartItem extends Model
{
    use SoftDeletes;

    protected $table = 'cart_items';

    protected $fillable = ['user_id', 'product_id', 'quantity'];

    public function product_details(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
