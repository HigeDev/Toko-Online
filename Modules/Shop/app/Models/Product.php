<?php

namespace Modules\Shop\Models;

use App\Models\User;
use App\Traits\UuidTrait;
use Modules\Shop\Models\Tag;
use Modules\Shop\Models\Category;
use Modules\Shop\Models\OrderItem;
use Modules\Shop\Models\ProductImage;
use Illuminate\Database\Eloquent\Model;
use Modules\Shop\Models\ProductInventory;
use Modules\Shop\Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, UuidTrait;

    protected $fillable = [
        'parent_id',
        'user_id',
        'sku',
        'type',
        'name',
        'slug',
        'price',
        'featured_image',
        'sale_price',
        'status',
        'stock_status',
        'manage_stock',
        'publish_date',
        'excerpt',
        'body',
        'metas',
        'weight'
    ];
    protected $table = 'shop_products';
    public const DRAFT = 'DRAFT';
    public const ACTIVE = 'ACTIVE';
    public const INACTIVE = 'INACTIVE';
    public const STATUSES = [
        self::DRAFT => 'Draft',
        self::ACTIVE => 'Active',
        self::INACTIVE => 'Inactive',
    ];
    public const STATUS_IN_STOCK = 'IN_STOCK';
    public const STATUS_OUT_OF_STOCK = 'OUT_OF_STOCK';
    public const STOCK_STATUSES = [
        self::STATUS_IN_STOCK => 'In Stock',
        self::STATUS_OUT_OF_STOCK => 'Out of Stock',
    ];
    public const SIMPLE = 'SIMPLE';
    public const CONFIGURABLE = 'CONFIGURABLE';
    public const TYPES = [
        self::SIMPLE => 'Simple',
        self::CONFIGURABLE => 'Configurable'
    ];

    protected static function newFactory()
    {
        return ProductFactory::new();
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function inventory()
    {
        return $this->hasOne(ProductInventory::class, 'product_id');
    }
    public function variants()
    {
        return $this->hasMany(Product::class, 'parent_id')->orderBy('price', 'ASC');
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'shop_categories_products', 'product_id', 'category_id');
    }
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'shop_products_tags', 'product_id', 'tag_id');
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'produk_id');
    }
    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class, 'product_id');
    }
    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }
    public function getPriceLabelAttribute()
    {
        return number_format($this->price);
    }
    public function getHasSalePriceAttribute()
    {
        return $this->sale_price != null;
    }
    public function getSalePriceLabelAttribute()
    {
        return number_format($this->sale_price);
    }
    public function getDiscountPercentAttribute()
    {
        $discountPercent = (($this->price - $this->sale_price) / $this->price) * 100;
        return number_format($discountPercent);
    }
    public function getStockStatusLabelAttribute()
    {
        return self::STOCK_STATUSES[$this->stock_status];
    }
    public function getStockAttribute()
    {
        if (!$this->inventory) {
            return 0;
        }
        return $this->inventory->qty;
    }
}
