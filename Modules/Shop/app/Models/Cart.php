<?php

namespace Modules\Shop\Models;

use App\Models\User;
use App\Traits\UuidTrait;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Modules\Shop\Models\CartItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    use HasFactory, UuidTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'shop_carts';
    protected $fillable = [
        'user_id',
        'expired_at',
        'base_total_price',
        'discount_amount',
        'discount_percent',
        'tax_percent',
        'tax_amount',
        'grand_total',
        'total_weight'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function items()
    {
        return $this->hasMany(CartItem::class);
    }
    public function scopeForUser(Builder $query, User $user): void
    {
        $query->where('user_id', $user->id);
    }
    public function getGrandTotalLabelAttribute()
    {
        return number_format($this->grand_total);
    }
    public function getDiscountAmountLabelAttribute()
    {
        return number_format($this->discount_amount);
    }
    public function getTaxAmountLabelAttribute()
    {
        return number_format($this->tax_amount);
    }
    public function getBaseTotalPriceLabelAttribute()
    {
        return number_format($this->base_total_price);
    }
    public function getSubTotalPriceLabelAttribute()
    {
        return number_format($this->base_total_price - $this->discount_amount);
    }
}
