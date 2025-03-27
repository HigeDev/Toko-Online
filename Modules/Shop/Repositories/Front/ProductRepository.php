<?php

namespace Modules\Shop\Repositories\Front;

use Modules\Shop\Models\Tag;
use Modules\Shop\Models\Product;
use Modules\Shop\Models\Category;
use Modules\Shop\Models\Order;
use Modules\Shop\Models\ProductInventory;
use Modules\Shop\Repositories\Front\Interfaces\ProductRepositoryInterface;

class ProductRepository implements ProductRepositoryInterface
{
    public function findAll($options = [])
    {
        $perPage = $options['per_page'] ?? null;
        $categorySlug = $options['filter']['category'] ?? null;
        $tagSlug = $options['filter']['tag'] ?? null;
        $priceFilter = $options['filter']['price'] ?? null;
        $products = Product::with(['categories', 'tags']);
        $sort = $options['sorting'] ?? null;

        if ($categorySlug) {
            $category = Category::where('slug', $categorySlug)->firstOrFail();
            $childCategoryIDs = Category::childIDs($category->id);
            $categoryIDs = array_merge([$category->id], $childCategoryIDs);
            $products = $products->whereHas('categories', function ($query) use ($categoryIDs) {
                $query->whereIn('shop_categories.id', $categoryIDs);
            });
        }
        if ($tagSlug) {
            $tag = Tag::where('slug', $tagSlug)->firstOrFail();
            $products = $products->whereHas('tags', function ($query) use ($tag) {
                $query->where('shop_tags.id', $tag->id);
            });
        }
        if ($priceFilter) {
            $products = $products->where('price', '>=', $priceFilter['min'])->where('price', '<=', $priceFilter['max']);
        }
        if ($sort) {
            $products = $products->orderBy($sort['sort'], $sort['order']);
        }
        if ($perPage) {
            return $products->paginate($perPage);
        }

        return $products->get();
    }
    public function findBySKU($sku)
    {
        return Product::where('sku', $sku)->firstOrFail();
    }
    public function findById($id)
    {
        return Product::where('id', $id)->firstOrFail();
    }
    public function findBySlug($slug)
    {
        return Product::where('slug', $slug)->first();
    }
    public function findProductCategoryInventory(){
        return Product::with(['categories','inventory'])->get();
    }
    public function storeProduct($product)
    {
        $x = Product::create([
            'user_id'=>$product['user_id'],
            'sku'=>$product['sku'],
            'type' =>$product['type'],
            'name'=>$product['name'],
            'slug' =>$product['slug'],
            'price'=>$product['price'],
            'sale_price'=>$product['sale_price'],
            'status' =>$product['status'],
            'manage_stock' =>$product['manage_stock'],
            'weight'=>$product['weight'],
            'publish_date' =>$product['publish_date'],
            'excerpt'=>$product['excerpt'],
            'body'=>$product['body'],
            'featured_image'=>$product['image'],
        ]);
        $idProduct = Product::where('sku', $product['sku'])->first();
        $x = ProductInventory::create([
            'product_id'=>$idProduct->id,
            'qty'=>$product['qty'],
            'low_stock_threshold'=>3
        ]);
        $x= $idProduct->categories()->sync($product['category']);
        return $x;
    }
    public function updateProduct($product){
        // Cari produk berdasarkan SKU
        $x = Product::where('sku', $product['sku'])->first();
        if (!$x) {
            return null; // Produk tidak ditemukan, kembalikan null
        }
        // Update data produk
        $x->update([
            'sku'       => $product['sku'],
            'name'      => $product['name'],
            'slug'      => $product['slug'],
            'price'     => $product['price'],
            'sale_price'=> $product['sale_price'],
            'weight'    => $product['weight'],
            'excerpt'   => $product['excerpt'],
            'body'      => $product['body']
        ]);
        // Periksa dan update gambar jika bukan 'no-photo.png'
        if (!empty($product['image']) && $product['image'] != 'no-photo.png') {
            $x->featured_image = $product['image'];
            $x->save();
        }
        // Update kategori produk
        if (!empty($product['category'])) {
            $x->categories()->sync($product['category']);
        }
        // Periksa dan update inventory jika ada
        $idInventory = ProductInventory::where('product_id', $product['id'])->first();
        if ($idInventory) {
            $idInventory->update([
                'qty' => $product['qty']
            ]);
        }else{
            $x = ProductInventory::create([
                'product_id'=>$x->id,
                'qty'=>$product['qty'],
                'low_stock_threshold'=>3
            ]);
        }
        return $x; // Kembalikan objek Product yang sudah diperbarui
    }
    public function findOrderProduct(){
        return Order::with(['orderItems.produk'])->get();
    }
    

}
