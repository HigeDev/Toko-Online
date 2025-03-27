@extends('themes.tokoonline.seller.layouts.app')
@section('content')
		<main class="content">
			<div class="container-fluid p-0">

				<h1 class="h3 mb-3">Order Page</h1>

				<div class="row col-12">
					<div class="card">
						 <div class="card-header">
							  <h5 class="card-title mb-0">Tabel Product</h5>
						 </div>
						 <div class="card-body group">
						 <table class="table" id="tableProduct">
							  <thead>
								 <tr>
									<th scope="col">Customer Name</th>
									<th scope="col">Code</th>
									<th scope="col">Status</th>
									<th scope="col">Order Date</th>
									<th scope="col">Payment Due</th>
									<th scope="col">Payment</th>
									<th scope="col">Total</th>
									<th scope="col" colspan="2">Action</th>
								 </tr>
							  </thead>
							  <tbody>
									@forelse ($orders as $orders)
								 <tr class="product-row">
									<td>{{ $orders->customer_first_name }}</td>
									<td>{{ $orders->code }}</td>
									<td>{{ $orders->status }}</td>
									<td>{{ date("H:i:s d-m-Y", strtotime($orders->order_date)) }}</td>
									<td>{{ date("H:i:s d-m-Y", strtotime($orders->payment_due)) }}</td>
									<td>
										<a class="" href="{{ $orders->payment_url }}"><i class="align-middle" data-feather="external-link"></i></a></td>
									<td>{{ $orders->grand_total }}</td>
									<td>
										 <button class="btn btn-success"><i class="align-middle" data-feather="edit"></i></button>
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

@endsection