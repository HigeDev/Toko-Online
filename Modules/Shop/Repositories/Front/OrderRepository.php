<?php

namespace Modules\Shop\Repositories\Front;

use Carbon\Carbon;
use App\Models\User;
use Midtrans\Config;
use Modules\Shop\Models\Cart;
use Modules\Shop\Models\Order;
use Modules\Shop\Models\Address;
use Modules\Shop\Models\Payment;
use Modules\Shop\Models\OrderItem;
use Illuminate\Support\Facades\Request;
use Modules\Shop\Repositories\Front\Interfaces\OrderRepositoryInterface;

class OrderRepository implements OrderRepositoryInterface
{
    public function create(User $user, Cart $cart, Address $address, $shipping = [])
    {
        $orderParams = $this->prepareOrderParams($user, $cart, $address, $shipping);
        $order = Order::create($orderParams);
        $order->orderItems()->saveMany($orderParams['items']);
        $order->payment_url = $this->generatePaymentUrl($order);
        $order->save();
        return $order;
    }
    private function prepareOrderParams(User $user, Cart $cart, Address $address, $shipping = []): array
    {
        $orderDate = Carbon::now();
        $paymentDue = $orderDate->addDay();
        $shippingFee = $shipping['shipping_fee'];
        $grandTotal = $cart->grand_total + $shippingFee;
        $params = [
            'user_id' => $user->id,
            'code' => Order::generateCode(),
            'status' => Order::STATUS_PENDING,
            'order_date' => $orderDate,
            'payment_due' => $paymentDue,
            'payment_url' => '',
            'base_total_price' => $cart->base_total_price,
            'tax_amount' => $cart->tax_amount,
            'tax_percent' => $cart->tax_percent,
            'discount_amount' => $cart->discount_amount,
            'discount_percent' => $cart->discount_percent,
            'shipping_cost' => $shippingFee,
            'grand_total' => $grandTotal,
            'customer_first_name' => $address->first_name,
            'customer_last_name' => $address->last_name,
            'customer_address1' => $address->address2,
            'customer_address2' => $address->address1,
            'customer_phone' => $address->phone,
            'customer_email' => $address->email,
            'customer_city' => $address->city,
            'customer_province' => $address->province,
            'customer_postcode' => $address->postcode,
        ];

        $items = [];
        if ($cart->items->count() > 0) {
            foreach ($cart->items as $item) {
                $itemBasePrice = $item->product->price;
                $itemSalePrice = $item->product->sale_price;
                $itemPrice = ($itemSalePrice > 0) ? $itemSalePrice : $itemBasePrice;
                $itemDiscountAmount = $itemBasePrice - $itemSalePrice;
                $itemDiscountPercent = ($itemDiscountAmount / $itemBasePrice) * 100;
                $itemTaxPercent = $cart->tax_percent;
                $itemTaxAmount = $itemPrice * $itemTaxPercent;
                $itemSubTotal = $itemPrice * $item->qty;
                $items[] = new OrderItem([
                    'product_id' => $item->product_id,
                    'qty' => $item->qty,
                    'base_price' => $itemBasePrice,
                    'base_total' => $itemBasePrice * $item->qty,
                    'tax_amount' => $itemTaxAmount,
                    'tax_percent' => $itemTaxPercent,
                    'discount_amount' => $itemDiscountAmount,
                    'discount_percent' => $itemDiscountPercent,
                    'sub_total' => $itemSubTotal,
                    'sku' => $item->product->sku,
                    'type' => $item->product->type,
                    'name' => $item->product->name,
                    'attributes' => '{}',
                ]);
            }
        }
        $params['items'] = $items;
        return $params;
    }

    private function generatePaymentUrl($order)
    {
        $this->initPaymentGateway();
        $customerDetails = [
            'first_name' => $order->customer_first_name,
            'email' => $order->customer_email
        ];

        $params
            = [
                'enable_payments' => Payment::PAYMENT_CHANNELS,
                'transaction_details' => [
                    'order_id' => $order->id,
                    'gross_amount' => ceil($order->grand_total),
                ],
                'customer_details' => $customerDetails,
                'expiry' => [
                    'start_time' => date('Y-m-d H:i:s T'),
                    'unit' => Payment::EXPIRY_UNIT,
                    'duration' => Payment::EXPIRY_DURATION,
                ]
            ];
        try {
            $snap = \Midtrans\Snap::createTransaction($params);
        } catch (\Exception $e) {
            throw $e;
        }
        return $snap->redirect_url;
    }
    private function initPaymentGateway()
    {
        // Set your Merchant Server Key
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        Config::$isProduction = (bool)env('MIDTRANS_PRODUCTION', false);
        // Set sanitization on (default)
        Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        Config::$is3ds = true;
    }

    public function findConfirmedOrders(){
        $x = Order::with(['orderItems'])->where('status', 'CONFIRMED')->get();
        return $x;
    }
    public function findDeliveredOrders(){
        $x = Order::with(['orderItems'])->where('status', 'DELIVERED')->get();
        return $x;
    }
    public function actionOrder($order){
        $x= Order::where('id', $order['id'])->firstOrFail();
        $x->update([
            'status'=>$order['status']
        ]);
        $x->save();
        return $x;
    }
    public function findById($id){
        $x = Order::with(['orderItems'])->where('id', $id)->firstOrFail();
        return $x;
    }
    public function findByUser($userId){
        $x = Order::with(['orderItems.produk'])->where('user_id', $userId->id)->get();
        return $x;
    }
}
