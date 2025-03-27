<?php

use Illuminate\Support\Facades\Route;
use Modules\Shop\Http\Controllers\SellerController;
use Modules\Shop\Http\Controllers\CartController;
use Modules\Shop\Http\Controllers\ShopController;
use Modules\Shop\Http\Controllers\OrderController;
use Modules\Shop\Http\Controllers\PaymentController;
use Modules\Shop\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//seller Product
Route::get('/seller/products', [SellerController::class, 'products'])->name('seller.products');
Route::post('/seller/store-product', [SellerController::class, 'storeProduct'])->name('seller.store_product');
Route::put('/seller/update-product/{id}', [SellerController::class, 'updateProduct'])->name('seller.update_product');

//seller Order

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/category/{categorySlug}', [ProductController::class, 'category'])->name('products.category');
Route::get('/tag/{tagSlug}', [ProductController::class, 'tag'])->name('products.tag');

Route::middleware(['auth'])->group(function () {
    Route::get('/carts', [CartController::class, 'index'])->name('carts.index');
    Route::post('/carts', [CartController::class, 'store'])->name('carts.store');
    Route::get('/carts/{id}/remove', [CartController::class, 'destroy'])->name('carts.destroy');
    Route::put('/carts', [CartController::class, 'update'])->name('carts.update');
    //checkout
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');
    Route::post('/orders/checkout', [OrderController::class, 'store'])->name('orders.store');
    Route::post('/orders/shipping-fee', [OrderController::class, 'shippingFee'])->name('orders.shipping_fee');
    Route::post('/orders/choose-package', [OrderController::class, 'choosePackage'])->name('orders.choose_package');

    
    Route::post('/payments/midtrans', [PaymentController::class, 'midtrans'])->name('payments.midtrans');
    
    Route::get('/seller', [SellerController::class, 'index'])->name('seller.index');
    Route::get('/seller/orders', [SellerController::class, 'orders'])->name('seller.orders');
    Route::get('/seller/order/allOrders', [SellerController::class, 'allOrders'])->name('seller.allOrders');
    Route::get('/seller/order/confirmedOrders', [SellerController::class, 'confirmedOrders'])->name('seller.confirmedOrders');
    Route::get('/seller/order/deliveredOrders', [SellerController::class, 'deliveredOrders'])->name('seller.deliveredOrders');
    Route::put('/seller/{id}/actionOrder', [SellerController::class, 'actionOrder'])->name('seller.actionOrder');
    Route::get('/seller/{id}/detailOrder', [SellerController::class, 'detailOrder'])->name('seller.detailOrder');
    Route::get('/seller/{id}/downloadInvoice', [SellerController::class, 'downloadInvoice'])->name('seller.downloadInvoice');
});

Route::get('/{categorySlug}/{productSlug}', [ProductController::class, 'show'])->name('products.show');

Route::group([], function () {
    Route::resource('shop', ShopController::class)->names('shop');
});
