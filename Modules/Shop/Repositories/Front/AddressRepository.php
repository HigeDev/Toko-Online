<?php

namespace Modules\Shop\Repositories\Front;

use App\Models\User;
use Modules\Shop\Models\Address;
use Modules\Shop\Repositories\Front\Interfaces\AddressRepositoryInterface;

class AddressRepository implements AddressRepositoryInterface
{
    public function findByUser(User $user)
    {
        return Address::where('user_id', $user->id)->get();
    }
    public function findById(string $id)
    {
        return Address::findOrFail($id);
    }
}
