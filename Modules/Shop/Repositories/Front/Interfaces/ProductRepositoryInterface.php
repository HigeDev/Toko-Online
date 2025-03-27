<?php

namespace Modules\Shop\Repositories\Front\Interfaces;

interface ProductRepositoryInterface
{
    public function findAll($options = []);
    public function findBySKU($sku);
    public function findById($id);
    public function findBySlug($slug);
    public function storeProduct($product);
    public function updateProduct($product);
    public function findProductCategoryInventory();
    public function findOrderProduct();
}
