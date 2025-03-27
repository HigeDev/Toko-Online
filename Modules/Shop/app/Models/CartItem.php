<?php

namespace Modules\Shop\Models;

use App\Traits\UuidTrait;
use Modules\Shop\Models\Cart;
use Modules\Shop\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CartItem extends Model
{
    use HasFactory, UuidTrait;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'shop_cart_items';
    protected $fillable = [
        'cart_id',
        'product_id',
        'qty'
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function getSubTotalAttribute()
    {
        return number_format($this->qty * $this->product->price);
    }
}
