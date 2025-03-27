<?php

namespace Modules\Shop\Repositories\Front;

use Modules\Shop\Models\Category;
use Modules\Shop\Repositories\Front\Interfaces\CategoryRepositoryInterface;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function findAll($options = [])
    {
        return Category::orderBy('name', 'asc')->get();
    }
    public function findBySlug($slug)
    {
        $aaa = Category::where('slug', $slug)->firstOrFail();
        return $aaa;
    }
}
