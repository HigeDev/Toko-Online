<?php

namespace Modules\Shop\Repositories\Front\Interfaces;

use App\Models\User;
use Modules\Shop\Models\Address;
use Modules\Shop\Models\Cart;

interface OrderRepositoryInterface
{
    public function create(User $user, Cart $cart, Address $address, $shipping = []);
    public function findConfirmedOrders();
    public function findById($id);
    public function findByUser($userId);
    public function actionOrder($order);
}
