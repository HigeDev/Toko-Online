<?php

namespace Modules\Shop\Models;

use Illuminate\Support\Facades\DB;
use Modules\Shop\Models\OrderItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\UuidTrait;
// use Modules\Shop\Database\Factories\OrderFactory;

class Order extends Model
{
    use HasFactory, UuidTrait;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'shop_orders';
    protected $fillable = [
        'user_id',
        'code',
        'status',
        'approved_by',
        'approved_at',
        'cancelled_by',
        'cancelled_at',
        'cancellation_note',
        'order_date',
        'payment_due',
        'payment_url',
        'base_total_price',
        'tax_amount',
        'tax_percent',
        'discount_amount',
        'discount_percent',
        'shipping_cost',
        'grand_total',
        'customer_note',
        'customer_first_name',
        'customer_last_name',
        'customer_address1',
        'customer_address2',
        'customer_phone',
        'customer_email',
        'customer_city',
        'customer_province',
        'customer_postcode',
    ];

    // protected static function newFactory(): OrderFactory
    // {
    //     // return OrderFactory::new();
    // }

    public const STATUS_PENDING = 'PENDING';
    public const STATUS_CONFIRMED = 'CONFIRMED';
    // public const STATUS_PACKAGING = 'PACKAGING';
    public const STATUS_DELIVERED = 'DELIVERED';
    public const STATUS_RECEIVED = 'RECEIVED';
    public const STATUS_CANCELLED = 'CANCELLED';
    public const STATUS_RETURNED = 'RETURNED';
    public const ORDER_CODE = 'ORDER';


    public static function generateCode()
    {
        $dateCode = self::ORDER_CODE . '/' . date('Y') . '/' . date('m') . '/' . date('d') .
            '/';
        $lastOrder = self::select([DB::raw('MAX(shop_orders.code) AS last_code')])
            ->where('code', 'like', $dateCode . '%')
            ->first();
        $lastOrderCode = !empty($lastOrder) ? $lastOrder['last_code'] : null;
        $orderCode = $dateCode . '00001';
        if ($lastOrderCode) {
            $lastOrderNumber = str_replace($dateCode, '', $lastOrderCode);
            $nextOrderNumber = sprintf('%05d', (int)$lastOrderNumber + 1);
            $orderCode = $dateCode . $nextOrderNumber;
        }
        if (self::isCodeExists($orderCode)) {
            return self::generateCode();
        }
        return $orderCode;
    }

    private static function isCodeExists($orderCode)
    {
        return Order::where('code', '=', $orderCode)->exists();
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }
}
