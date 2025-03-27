<?php

namespace Modules\Shop\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Modules\Shop\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Modules\Shop\Repositories\Front\Interfaces\OrderRepositoryInterface;
use Modules\Shop\Repositories\Front\Interfaces\ProductRepositoryInterface;
use Modules\Shop\Repositories\Front\Interfaces\CategoryRepositoryInterface;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class SellerController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:seller', only: ['index','products','storeProduct','updateProduct','allOrders','confirmedOrders','deliveredOrders','actionOrders','detailOrders','downloadInvoice']),
        ];
    }
    protected $productRepository;
    protected $categoryRepository;
    protected $orderRepository;
    public function __construct(ProductRepositoryInterface $productRepository, CategoryRepositoryInterface $categoryRepository, OrderRepositoryInterface $orderRepository) {
        $this->productRepository=$productRepository;
        $this->categoryRepository=$categoryRepository;
        $this->orderRepository=$orderRepository;
    }
    public function index()
    {
        return $this->loadTheme('seller.dashboard');
    }
    public function products()
    {
        $this->data['categories'] = $this->categoryRepository->findAll();
        $this->data['products'] = $this->productRepository->findProductCategoryInventory();
        // dd($this->data);
        return $this->loadTheme('seller.product', $this->data);
    }
    public function storeProduct(Request $request)
    {
        $user_id=auth()->user()->id;
        $manageStock = (bool)random_int(0, 1);
        if ($request->hasFile('product_image')) {
            $image=$request->product_image->hashname();
        } else {
            $image='no-photo.png';
        }
        $product=[
            'user_id'=>$user_id,
            'sku'=>$request->product_sku,
            'type' => Product::SIMPLE,
            'name'=>$request->product_name,
            'slug' => Str::slug($request->product_name),
            'price'=>$request->product_price,
            'sale_price'=>$request->product_sale_price,
            'status' => Product::ACTIVE,
            'manage_stock' => $manageStock,
            'weight'=>$request->product_weight,
            'publish_date' => now(),
            'excerpt'=>$request->product_excerpt,
            'body'=>$request->product_body,
            'image'=>$image,
            'qty'=>$request->product_qty,
            'category'=>$request->categories
        ];
        // dd($categories[0]);
        DB::beginTransaction();
        try {
            if ($request->hasFile('product_image')) {
                $request->product_image->storeAs('img/productImage', $image, 'public');
            }
            $product = $this->productRepository->storeProduct($product);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
        DB::commit();
        return redirect(route('seller.products'))->with('success', 'Berhasil menambahkan produk baru');
    }
    public function updateProduct(Request $request)
    {
        // dd($request);
        if ($request->hasFile('product_image')) {
            $image=$request->product_image->hashname();
        } else {
            $image='no-photo.png';
        }
        $product=[
            'id'=>$request->product_id,
            'sku'=>$request->product_sku,
            'name'=>$request->product_name,
            'slug' => Str::slug($request->product_name),
            'price'=>$request->product_price,
            'sale_price'=>$request->product_sale_price,
            'weight'=>$request->product_weight,
            'excerpt'=>$request->product_excerpt,
            'body'=>$request->product_body,
            'image'=>$image,
            'category'=>$request->categories,
            'qty'=>$request->product_qty
        ];
        DB::beginTransaction();
        try {
            if ($request->hasFile('product_image')) {
                $request->product_image->storeAs('img/productImage', $image, 'public');
            }
            $product = $this->productRepository->updateProduct($product);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
        DB::commit();
        return redirect(route('seller.products'))->with('success', 'Berhasil mengubah data produk');
    }
    public function allOrders()
    {
        $this->data['orders'] = $this->productRepository->findOrderProduct();
        return $this->loadTheme('seller.order', $this->data);
    }
    public function confirmedOrders()
    {
        $this->data['orders'] = $this->orderRepository->findConfirmedOrders();
        return $this->loadTheme('seller.confirmedOrder', $this->data);
    }
    public function deliveredOrders()
    {
        $this->data['orders'] = $this->orderRepository->findDeliveredOrders();
        return $this->loadTheme('seller.deliveredOrder', $this->data);
    }
    public function actionOrder(Request $request, $id){
        // dd($id);
        $order=[
            'id'=>$id,
            'status'=>$request->status
        ];
        DB::beginTransaction();
        try {
            $order =  $this->orderRepository->actionOrder($order);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
        DB::commit();
        return redirect(route('seller.confirmedOrders'))->with('success', 'Berhasil mengubah data produk');
    }
    public function detailOrder($id){
        $this->data['orders'] = $this->orderRepository->findById($id);
        return $this->loadTheme('seller.detailOrder', $this->data);
    }
    public function downloadInvoice($id){
        // Ambil data order berdasarkan ID
        $orders = $this->orderRepository->findById($id);

        // Generate PDF menggunakan view
        $pdf = Pdf::loadView('themes.tokoonline.seller.invoicePdf', compact('orders'));

        // Download file PDF
        return $pdf->download("ORDER-2025-02-23-00001.pdf");
    }
}