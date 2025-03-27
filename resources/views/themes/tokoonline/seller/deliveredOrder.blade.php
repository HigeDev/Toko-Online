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
									<th scope="col">Total Item</th>
									<th scope="col">Total</th>
									<th scope="col" colspan="3">Action</th>
								 </tr>
							  </thead>
							  <tbody>
									@forelse ($orders as $order)
								 <tr class="product-row">
									<td>{{ $order->customer_first_name }}</td>
									<td>
										<a href="{{ route('seller.detailOrder', $order->id) }}"><i class="align-middle" data-feather="external-link"></i>{{ $order->code }}</a>
									</td>
									<td>{{ $order->status }}</td>
									<td>{{ date("H:i:s d-m-Y", strtotime($order->order_date)) }}</td>
									<td>{{ count($order->orderItems) }}</td>
									<td>Rp.{{number_format($order->grand_total, 0, ',', '.') }}</td>
									<td>
										 <a href="{{ route('seller.downloadInvoice', $order->id) }}" class="btn btn-info"><i class="align-middle" data-feather="download"></i> Invoice</a>
									</td>
									<td>
										 <form action="{{ route('seller.actionOrder', $order->id) }}" method="POST" style="display:inline;" onsubmit="return confirmAction(event)">
											 @csrf
											 <input type="hidden" name="status" value="DELIVERED">
											 @method('PUT')
											 <button class="btn btn-success"><i class="align-middle" data-feather="download"></i> Kirim</button>
										</form>
									</td>
									<td>
										<form action="{{ route('seller.actionOrder', $order->id) }}" method="POST" style="display:inline;" onsubmit="return confirmAction(event)">
											@csrf
											<input type="hidden" name="status" value="CANCELLED">
											@method('PUT')
											<button class="btn btn-danger"><i class="align-middle" data-feather="x"></i> Cancel</button>
									  </form>
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
		  <script>
				function confirmAction(event) {
					 event.preventDefault(); // Mencegah form submit langsung
		  
					 if (confirm("Apakah Anda yakin?")) {
						  event.target.submit(); // Submit form jika user menekan OK
					 }
				}
		  </script>
		  
		</main>

@endsection