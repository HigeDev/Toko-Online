@extends('themes.tokoonline.seller.layouts.app')
@section('content')
    <main class="content">
        <div class="container-fluid p-0">
            <h1 class="h3 mb-3">Product</h1>
            <div class="row col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0" id="cardTitle">Input Product</h5>
                        <div class="d-flex" id="btnClick">
                            <button type="btn" class="btn btn-success ms-auto" style="display: none" id="btnNew">New Product</button>
                        </div>
                    </div>
                    @include('themes.tokoonline.shared.flash')
                    <form method="POST" action="{{ route('seller.store_product') }}" id="formProduct" enctype="multipart/form-data">
                    @csrf
                        <div class="card-body group">
                            <div class="row">
                                <input type="hidden" name="product_id" id="product_id" :value="old('product_id')">
                                <div class="col-lg-4">
                                    <div class="mb-3 row">
                                        <label for="product_image" class="col-sm-2 col-form-label">Image</label>
                                        <div class="col-sm-10">
                                        <input type="file" class="form-control" name="product_image" id="product_image" :value="old('product_image')">
                                        </div>
                                    </div>
                                    <div class="mb-3 row align-self-center d-flex">
                                        <img src="{{ asset('/img/productImage/no-photo.png') }}" id="preview" class="img-fluid w-50 mx-auto" alt="...">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <input type="hidden" name="_method" id="methodField" value="POST"> 
                                    <div class="mb-3 row">
                                        <label for="product_sku" class="col-sm-2 col-form-label">SKU</label>
                                        <div class="col-sm-10">
                                        <input type="text" class="form-control" name="product_sku" id="product_sku" placeholder="Product SKU..." :value="old('product_sku')" required>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="product_name" class="col-sm-2 col-form-label">Name</label>
                                        <div class="col-sm-10">
                                        <input type="text" class="form-control" name="product_name" id="product_name" placeholder="Product Name..." :value="old('product_name')" required>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="product_price" class="col-sm-2 col-form-label">Price</label>
                                        <div class="col-sm-10">
                                        <input type="text" class="form-control" name="product_price" id="product_price" placeholder="Product Price..." :value="old('product_price')" required>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="product_sale_price" class="col-sm-2 col-form-label">Sale Price</label>
                                        <div class="col-sm-10">
                                        <input type="text" class="form-control" name="product_sale_price" id="product_sale_price" placeholder="Product Sale Price..." :value="old('product_sale_price')" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="mb-3 row">
                                        <label for="product_weight" class="col-sm-2 col-form-label">Weight</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="product_weight" id="product_weight" placeholder="Product Weight..." :value="old('product_weight')" required>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="product_excerpt" class="col-sm-2 col-form-label">Excerpt</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="product_excerpt" id="product_excerpt" placeholder="Product Excerpt..." :value="old('product_excerpt')" required>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="product_body" class="col-sm-2 col-form-label">Body</label>
                                        <div class="col-sm-10">
                                            <textarea type="text" class="form-control" name="product_body" id="product_body" placeholder="Product Body...">{{ old('product_body') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="product_qty" class="col-sm-2 col-form-label">Qty</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="product_qty" id="product_qty" placeholder="Product Qty..." :value="old('product_qty')" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                            <div class="mb-3 col-lg-4">
                            </div>
                            <div class="col-lg-7">
                                <div class="col-sm-9">
                                    @forelse ($categories as $category)
                                        <input class="form-check-input" type="checkbox" value="{{ $category->id }}" name="categories[]">
                                        <label class="form-check-label" for="flexCheckDefault">
                                            {{ $category->name }}
                                        </label>
                                    @empty
                                        <p>Product is empty</p>
                                    @endforelse
                                </div>
                            </div>
                            <div class="col-lg-1">
                                <div class="mb-3 self-center d-flex">
                                    <button type="submit" class="btn btn-primary ms-auto">Input</button>
                                </div>
                            </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Tabel Product</h5>
                    </div>
                    <div class="card-body group">
                    <table class="table" id="tableProduct">
                        <thead>
                          <tr>
                            <th scope="col">Image</th>
                            <th scope="col">SKU</th>
                            <th scope="col">Name</th>
                            <th scope="col">Price</th>
                            <th scope="col">Sale Price</th>
                            <th scope="col">Weight</th>
                            <th scope="col">Excerpt</th>
                            <th scope="col">Body</th>
                            <th scope="col">Qty</th>
                            <th scope="col">category</th>
                            <th scope="col" colspan="2">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $product)
                          <tr class="product-row">
                            <td><img src="{{ asset('storage/img/productImage/'.$product->featured_image) }}" id="preview" class="img-fluid w-100 mx-auto" alt="..."></td>
                            <td>{{ $product->sku }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->price }}</td>
                            <td>{{ $product->sale_price }}</td>
                            <td>{{ $product->weight }}</td>
                            <td>{{ $product->excerpt }}</td>
                            <td>{{ $product->body }}</td>
                            <td>{{ $product->inventory?->qty }}</td>
                            <td data-categories='[1, 3]'>
                                @foreach($product->categories as $category)
                                    {{ $category->name }}
                                    @if(!$loop->last), @endif
                                @endforeach
                            </td>
                            <td>
                                <button class="btn btn-success" onclick="updateForm('{{$product->id}}', '{{$product->sku}}', '{{ $product->name }}', '{{$product->price}}', '{{$product->sale_price}}', '{{$product->weight}}', '{{$product->excerpt}}','{{$product->body}}','{{$product->inventory?->qty}}','{{$product->slug}}' )"><i class="align-middle" data-feather="edit"></i></button>
                            </td>
                            <td>
                                <button class="btn btn-danger"><i class="align-middle" data-feather="trash-2"></i></button>
                            </td>
                          </tr>
                          @empty
                          <p>Product is empty</p>
                          @endforelse
                        </tbody>
                      </table>
                </div>
            </div>
        </div>
    </main>
    <script>
        function updateForm(id, sku, name, price, sale_price, weight, excerpt, body, qty, slug) {
            document.getElementById('cardTitle').innerText = "Update Product";
            document.getElementById("btnNew").style.display = 'block';

            document.getElementById("product_id").value = id;
            document.getElementById("product_sku").value = sku;
            document.getElementById("product_name").value = name;
            document.getElementById("product_price").value = price;
            document.getElementById("product_sale_price").value = sale_price;
            document.getElementById("product_weight").value = weight;
            document.getElementById("product_excerpt").value = excerpt;
            document.getElementById("product_body").value = body;
            document.getElementById("product_qty").value = qty;

            let form = document.getElementById('formProduct');
            let methodField = document.getElementById('methodField');

            // Ubah action untuk update (PUT)
            form.action = "/seller/update-product/" + slug;
            
            // Ubah method menjadi PUT
            methodField.value = 'PUT';
        }
        document.getElementById('btnClick').addEventListener('click', function () {
            document.getElementById('cardTitle').innerText = "Input Product";
            document.getElementById("btnNew").style.display = 'none';
            
            document.getElementById('formProduct').reset();

            let form = document.getElementById('formProduct');
            let methodField = document.getElementById('methodField');

            // Ubah action untuk update (PUT)
            form.action = "/seller/store-product";
            
            // Ubah method menjadi PUT
            methodField.value = 'POST';
        });

        $(document).ready(function() {
        $('#product_image').change(function(event) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#preview').attr('src', e.target.result).show();
            }
            reader.readAsDataURL(event.target.files[0]);
        });
    });
    </script>
@endsection