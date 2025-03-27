<?php

namespace Modules\Shop\Models;

use App\Traits\UuidTrait;
use Modules\Shop\Models\Order;
use Modules\Shop\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Shop\Database\Factories\OrderItemFactory;

class OrderItem extends Model
{
    use HasFactory, UuidTrait;

    /**
     * The attributes that are mass assignable.
     */

    protected $table = 'shop_order_items';
    protected $fillable = [
        'order_id',
        'product_id',
        'qty',
        'base_price',
        'base_total',
        'tax_amount',
        'tax_percent',
        'discount_amount',
        'discount_percent',
        'sub_total',
        'sku',
        'type',
        'name',
        'attributes',
    ];

    // protected static function newFactory(): OrderItemFactory
    // {
    //     // return OrderItemFactory::new();
    // }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
    public function produk()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
