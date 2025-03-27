@extends('themes.tokoonline.layouts.app')
@section('content')
    <section class="breadcrumb-section pb-4 pb-md-4 pt-4 pt-md-4">
        <div class="container">
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Orders</li>
                </ol>
            </nav>
        </div>
    </section>
    <section class="main-content">
        <div class="container">
            <div class="row">
                <section class="col-lg-12 col-md-12 shopping-cart">
                    <div class="card mb-4 bg-light border-0 section-header">
                        <div class="card-body p-5">
                            <h2 class="mb-0">Shopping Order</h2>
                        </div>
                    </div>
                    @include('themes.tokoonline.shared.flash')
                        <div class="container d-flex justify-content-center align-items-center">
                            <ul>
                                @forelse ($orders as $orders)
                                <li class="list-group-item mb-3">
                                    <div class="card m-" style="width: 50rem;">
                                        <div class="card-body">
                                          <div class="row">
                                            <div class="col-lg-6">
                                                <div class="d-flex align-items-center">
                                                    <p class="btn btn-success">#{{ $loop->iteration }}</p>
                                                    <h5 class="card-title">&nbsp;{{ $orders->code }}</h5>
                                                </div>
                                                <p class="card-text">{{ date("H:i:s d-m-Y", strtotime($orders->order_date)) }}</p>
                                            </div>
                                            <div class="col-lg-6" style="text-align: right;">
                                                <p class="card-text">{{ $orders->status }}</p>
                                                <b class="card-text">Total {{ count($orders->orderItems) }} Products: Rp.{{number_format($orders->grand_total, 0, ',', '.') }}</b><br>
                                                <button class="btn btn-success mb-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample-{{ $orders->code }}" aria-expanded="false" aria-controls="collapseExample">
                                                    &nbsp;&nbsp;&nbsp; <i class="bx bx-chevrons-right"></i>
                                                </button>
                                            </div>
                                            <div class="collapse" id="collapseExample-{{ $orders->code }}">
                                              <div class="card card-body">
                                                <div class="row">
                                                    @foreach ($orders->orderItems as $items)
                                                    <div class="col-lg-3">
                                                        <img src="{{ asset('storage/img/productImage/no-photo.png') }}" class="img-fluid rounded" style="width: 100px; height: 100px; object-fit: cover;">
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <div class="row">
                                                            <strong>{{ $items->name }}</strong>
                                                            <div class="col-lg-3">
                                                                <p class="mb-0" style="text-decoration: line-through;">Rp.{{number_format($items->base_price , 0, ',', '.') }}</p>Rp.{{number_format($items->sub_total/ $items->qty , 0, ',', '.') }}
                                                            </div>
                                                            <div class="col-lg-2">
                                                                <p>x{{$items->qty }}</p>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <p class="mb-0">Rp.{{number_format($items->sub_total , 0, ',', '.') }}</p> Tax: Rp.{{number_format($items->tax_amount * $items->qty , 0, ',', '.') }} ({{ $items->tax_percent }}%)
                                                            </div>
                                                            <div class="col-lg-3" style="text-align: right;">
                                                                <br>
                                                                <strong>Rp.{{number_format($items->sub_total + ($items->tax_amount * $items->qty) , 0, ',', '.') }}</strong>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    @endforeach
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                    </div>
                                </li>
                                @empty
                                <li class="list-group-item mb-3">
                                    <div class="card m-" style="width: 50rem;">
                                        <div class="card-body">
                                          <h5 class="card-title">No Order Found</h5>
                                        </div>
                                    </div>
                                </li>
                                @endforelse
                            </ul>
                        </div>
                </section>
            </div>
        </div>
    </section>
@endsection
