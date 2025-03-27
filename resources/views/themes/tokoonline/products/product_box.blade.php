<div class="col-lg-3 col-6">
    <div class="card card-product card-body p-lg-4 p3">
        <a href="{{ shop_product_link($product) }}">
            <div class="d-flex justify-content-center">
                <img style="max-height: 100px" src="{{ asset('storage/img/productImage/'.$product->featured_image) }}" alt="" class="img-fluid">
            </div>
        </a>
        <h3 class="product-name mt-3">{{ $product->name }}</h3>
        <div class="rating">
            <i class="bx bxs-star"></i>
            <i class="bx bxs-star"></i>
            <i class="bx bxs-star"></i>
            <i class="bx bxs-star"></i>
            <i class="bx bxs-star"></i>
        </div>
        <div class="detail d-flex justify-content-between align-items-center mt-4">
            <p class="price">IDR {{ $product->price_label }}</p>
            <a href="product.html" class="btn-cart"><i class="bx bx-cart-alt"></i></a>
        </div>
    </div>
</div>
