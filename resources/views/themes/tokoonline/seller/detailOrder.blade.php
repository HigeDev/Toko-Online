@extends('themes.tokoonline.seller.layouts.app')
@section('content')
		<main class="content">
			<div class="container-fluid p-0">

				<h1 class="h3 mb-3">Order Page</h1>

				<div class="row col-12">
					<div class="card">
						<div class="card-header mb-0">
							<h5 class="card-title mb-0">Detail Invoice</h5>
							<div class="d-flex" id="btnClick">
								 <a href="{{ route('seller.downloadInvoice', $orders->id) }}" class="btn btn-success ms-auto" id="btnNew">Download Invoice</a>
							</div>
						</div>
						<div class="card-body group">
							<div class="row">
								<div class="card col-lg-4">
									<div class="m-3">
										<h3>Invoice</h3>
										<table>
											<tr>
												<td>Invoice Id</td>
												<td>&nbsp;&nbsp;&nbsp;:{{ $orders->id }}</td>
											</tr>
											<tr>
												<td>Code</td>
												<td>&nbsp;&nbsp;&nbsp;:{{ $orders->code }}</td>
											</tr>
											<tr>
												<td>Order date</td>
												<td>&nbsp;&nbsp;&nbsp;:{{ date("H:i:s d-m-Y", strtotime($orders->order_date)) }}</td>
											</tr>
											<tr>
												<td>Total Item</td>
												<td>&nbsp;&nbsp;&nbsp;:{{ count($orders->orderItems) }}</td>
											</tr>
										</table>
									</div>
								</div>
								<div class="card col-lg-4">
									<div class="m-3">
										<h3>Customer</h3>
										<table>
											<tr>
												<td>Name</td>
												<td>&nbsp;&nbsp;&nbsp;:{{ $orders->customer_first_name .' '. $orders->customer_last_name}}</td>
											</tr>
											<tr>
												<td>Address</td>
												<td>&nbsp;&nbsp;&nbsp;:{{ $orders->customer_address1 }}</td>
											</tr>
											<tr>
												<td>Phone Number</td>
												<td>&nbsp;&nbsp;&nbsp;:{{ $orders->customer_phone }}</td>
											</tr>
										</table>
									</div>
								</div>
								<div class="card col-lg-4">
									<div class="m-3">
										<h3>Total</h3>
										<table>
											<tr>
												<td>Subtotal Product</td>
												<td>&nbsp;&nbsp;&nbsp;:Rp.{{number_format($orders->base_total_price, 0, ',', '.') }}</td>
											</tr>
											<tr>
												<td>Discount</td>
												<td>&nbsp;&nbsp;&nbsp;:Rp.{{number_format($orders->discount_amount, 0, ',', '.') }}</td>
											</tr>
											<tr>
												<td>Tax</td>
												<td>&nbsp;&nbsp;&nbsp;:Rp.{{number_format($orders->tax_amount, 0, ',', '.') }}</td>
											</tr>
											<tr>
												<td><strong>Total</strong></td>
												<td><strong>&nbsp;&nbsp;&nbsp;:Rp.{{number_format($orders->grand_total, 0, ',', '.') }}</strong></td>
											</tr>
										</table>
									</div>
								</div>
							</div>
							<div class="card">
								<div class="m-3">
									<h3>Order Items</h3>
								</div>
								<table class="table">
									<thead>
									  <tr>
										 <th scope="col">#</th>
										 <th scope="col">Producy Name</th>
										 <th scope="col">Price</th>
										 <th scope="col">Qty</th>
										 <th scope="col">Subtotal</th>
									  </tr>
									</thead>
									<tbody>
										@forelse ($orders->orderItems as $item)
										<tr>
										  <th scope="row">{{ $loop->iteration }}</th>
										  <td>{{ $item->name }}</td>
										  <td>{{ $item->base_price }}</td>
										  <td>{{ $item->qty }}</td>
										  <td>{{ $item->base_total }}</td>
										</tr>
										@empty
										<p>Product is empty</p>
										@endforelse
									</tbody>
								 </table>
							</div>
						</div>
					</div>
				</div>
			</div>
		  
		</main>

@endsection