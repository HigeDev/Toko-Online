<?php

namespace Modules\Shop\Models;

use App\Traits\UuidTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Shop\Database\Factories\PaymentFactory;

class Payment extends Model
{
    use HasFactory, UuidTrait;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'shop_payments';
    protected $fillable = [
        'user_id',
        'order_id',
        'code',
        'payment_type',
        'status',
        'approved_by',
        'approved_at',
        'note',
        'rejected_by',
        'rejected_at',
        'rejection_note',
        'amount',
        'payloads',
    ];

    public const EXPIRY_DURATION = 1;
    public const EXPIRY_UNIT = 'days';
    public const PAYMENT_CHANNELS = [
        'mandiri_clickpay',
        'cimb_clicks',
        'bca_klikbca',
        'bca_klikpay',
        'bri_epay',
        'echannel',
        'permata_va',
        'bca_va',
        'bni_va',
        'other_va',
        'gopay',
        'indomaret',
        'danamon_online',
    ];

    // protected static function newFactory(): PaymentFactory
    // {
    //     // return PaymentFactory::new();
    // }
    public const PAYMENT_CODE = 'PAY';
    public static function generateCode()
    {
        $dateCode = self::PAYMENT_CODE . '/' . date('Y') . '/' . date('m') . '/' . date('d') .
            '/';
        $lastOrder = self::select([DB::raw('MAX(shop_payments.code) AS last_code')])
            ->where('code', 'like', $dateCode . '%')
            ->first();
        $lastOrderCode = !empty($lastOrder) ? $lastOrder['last_code'] : null;
        $paymentCode = $dateCode . '00001';
        if ($lastOrderCode) {
            $lastOrderNumber = str_replace($dateCode, '', $lastOrderCode);
            $nextOrderNumber = sprintf('%05d', (int)$lastOrderNumber + 1);
            $paymentCode = $dateCode . $nextOrderNumber;
        }
        if (self::isCodeExists($paymentCode)) {
            return self::generateCode();
        }
        return $paymentCode;
    }

    private static function isCodeExists($paymentCode)
    {
        return Payment::where('code', '=', $paymentCode)->exists();
    }
}
