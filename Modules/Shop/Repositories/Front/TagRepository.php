<?php

namespace Modules\Shop\Repositories\Front;

use Modules\Shop\Models\Tag;
use Modules\Shop\Repositories\Front\Interfaces\TagRepositoryInterface;

class TagRepository implements TagRepositoryInterface
{
    public function findAll($options = [])
    {
        return Tag::orderBy('name', 'asc')->get();
    }
    public function findBySlug($slug)
    {
        $qqq = Tag::where('slug', $slug)->firstOrFail();
        return $qqq;
    }
}
