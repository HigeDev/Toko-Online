<?php

namespace Modules\Shop\Http\Controllers;

use Midtrans\Config;
use Midtrans\Notification;
use Illuminate\Http\Request;
use Modules\Shop\Models\Order;
use Modules\Shop\Models\Payment;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class PaymentController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:payments', only: ['midtrans','initPaymentGateway'])
        ];
    }
    public function midtrans(Request $request)
    {
        $payload = $request->getContent();
        $notification = json_decode($payload);
        if ((bool)env('MIDTRANS_PRODUCTION', false)) {
            $validSignatureKey = hash("sha512", $notification->order_id . $notification->status_code . $notification->gross_amount . env('MIDTRANS_SERVER_KEY'));
            if ($notification->signature_key != $validSignatureKey) {
                return response(['code' => 403, 'message' => 'Invalid Signature Key'], 403);
            }
        }
        $this->initPaymentGateway();
        $paymentNotification = new Notification();
        $order = Order::where('id', $paymentNotification->order_id)->first();

        if (!$order) {
            return response(['code' => '404', 'message' => 'Order not found'], 404);
        }
        if ($order->status == Order::STATUS_CONFIRMED) {
            return response(['code' => '403', 'message' => 'Order already paid'], 403);
        }

        $transaction = $paymentNotification->transaction_status;
        $type = $paymentNotification->payment_type;
        $order_id = $paymentNotification->order_id;
        $fraud = $paymentNotification->fraud_status;
        $paymentSuccess = false;

        error_log($payload);
        error_log("Order ID $paymentNotification->order_id: " . "transaction status = $transaction, fraud staus = $fraud");

        if ($transaction == 'capture') {
            // For credit card transaction, we need to check whether transaction is challenge by FDS or not
            if ($type == 'credit_card') {
                if ($fraud == 'challenge') {
                    // TODO set payment status in merchant's database to 'Challenge by FDS'
                    // TODO merchant should decide whether this transaction is authorized or not in MAP
                    $paymentSuccess = false;
                } else {
                    // TODO set payment status in merchant's database to 'Success'
                    $paymentSuccess = true;
                }
            }
        } else if ($transaction == 'settlement') {
            // TODO set payment status in merchant's database to 'Settlement'
            $paymentSuccess = true;
        } else if ($transaction == 'pending') {
            // TODO set payment status in merchant's database to 'Pending'
            $paymentSuccess = false;
        } else if ($transaction == 'deny') {
            // TODO set payment status in merchant's database to 'Denied'
            $paymentSuccess = false;
        } else if ($transaction == 'expire') {
            // TODO set payment status in merchant's database to 'expire'
            $paymentSuccess = false;
        } else if ($transaction == 'cancel') {
            // TODO set payment status in merchant's database to 'Denied'
            $paymentSuccess = false;
        }

        $paymentParms = [
            'code' => Payment::generateCode(),
            'user_id' => $order->user_id,
            'order_id' => $order->id,
            'status' => $transaction,
            'payment gateway' => 'MIDTRANS',
            'payment_type' => $paymentNotification->payment_type,
            'amount' => $paymentNotification->gross_amount,
            'payloads' => $payload,
        ];
        $payment = Payment::create($paymentParms);
        if ($paymentSuccess && $payment) {
            DB::beginTransaction();
            try {
                $order->status = Order::STATUS_CONFIRMED;
                $order->save();
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
            DB::commit();
        }
        $message = 'Payment status is : ' . $transaction;
        return response(['code' => 200, 'message' => $message], 200);
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
}
