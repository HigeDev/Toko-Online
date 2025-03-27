<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />

		<title>A simple, clean, and responsive HTML invoice template</title>

		<!-- Favicon -->
		<link rel="icon" href="./images/favicon.png" type="image/x-icon" />
      <script src="https://unpkg.com/feather-icons"></script>
      <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>

		<!-- Invoice styling -->
		<style>
			body {
				font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
				text-align: center;
				color: #777;
			}

			body h1 {
				font-weight: 300;
				margin-bottom: 0px;
				padding-bottom: 0px;
				color: #000;
			}

			body h3 {
				font-weight: 300;
				margin-top: 10px;
				margin-bottom: 20px;
				font-style: italic;
				color: #555;
			}

			body a {
				color: #06f;
			}

			.invoice-box {
				max-width: 800px;
				margin: auto;
				padding: 30px;
				border: 1px solid #eee;
				box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
				font-size: 16px;
				line-height: 24px;
				font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
				color: #555;
			}

			.invoice-box table {
				width: 100%;
				line-height: inherit;
				text-align: left;
				border-collapse: collapse;
			}

			.invoice-box table td {
				padding: 5px;
				vertical-align: top;
			}

			.invoice-box table tr td:nth-child(2) {
				text-align: right;
			}

			.invoice-box table tr.top table td {
				padding-bottom: 20px;
			}

			.invoice-box table tr.top table td.title {
				font-size: 45px;
				line-height: 45px;
				color: #333;
			}

			.invoice-box table tr.information table td {
				padding-bottom: 40px;
			}

			.invoice-box table tr.heading td {
				background: #eee;
				border-bottom: 1px solid #ddd;
				font-weight: bold;
			}

			.invoice-box table tr.details td {
				padding-bottom: 20px;
			}

			.invoice-box table tr.item td {
				border-bottom: 1px solid #eee;
			}

			.invoice-box table tr.item.last td {
				border-bottom: none;
			}

			.invoice-box table tr.total td:nth-child(2) {
				border-top: 2px solid #eee;
				font-weight: bold;
			}

			@media only screen and (max-width: 600px) {
				.invoice-box table tr.top table td {
					width: 100%;
					display: block;
					text-align: center;
				}

				.invoice-box table tr.information table td {
					width: 100%;
					display: block;
					text-align: center;
				}
			}
         .circle-img {
            width: 100px;
            height: 100px;
            clip-path: circle();
         }
         .thick-hr {
            height: 2px; /* Ubah ketebalan garis */
            background-color: black; /* Warna garis */
            border: none; /* Hilangkan border default */
            width: 100%; /* Lebar penuh */
        }
		</style>
	</head>

	<body>

		<div class="invoice-box">
         <h2>Invoice</h2>
         <hr class="thick-hr">
			<table>
				<tr class="top">
					<td colspan="2">
						<table>
							<tr>
								<td class="title">
									<img src="{{ asset('img/bla.jpg') }}" class="circle-img" alt="Toko Online" style="width: 100%; max-width: 300px" />
								</td>

								<td>
									Invoice #: 123<br />
									Created: January 1, 2023<br />
									Due: February 1, 2023
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr class="information">
					<td colspan="2">
						<table>
							<tr>
								<td>
									<strong>{{ $orders->code }}</strong><br />
									{{ date("H:i:s d-m-Y", strtotime($orders->order_date)) }}<br />
									{{ count($orders->orderItems) }} Item<br />
                           Gopay
								</td>

								<td>
									<strong>{{ $orders->customer_first_name .' '. $orders->customer_last_name}}</strong><br />
									<strong>Address :</strong> <br />
                           {{ $orders->customer_address1 }}<br />
									{{ $orders->customer_phone }}
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
         <table>
				<tr class="heading">
					<td>#</td>
					<td style="text-align: left;">List Item</td>
					<td style="text-align: right;">Qty</td>
					<td style="text-align: right;" colspan="2">Price</td>
					<td style="text-align: right;">Tax</td>
					<td style="text-align: right;">Subtotal</td>
				</tr>

            @forelse ($orders->orderItems as $item)
				<tr class="item">
					<td>{{ $loop->iteration }}</td>
					<td style="text-align: left;">{{ $item->name }}</td>
					<td style="text-align: right;">{{ $item->qty }}</td>
					<td style="text-align: right; margin-right:0px; padding-right:0px; text-decoration: line-through;">Rp.{{number_format($item->base_price , 0, ',', '.') }}</td>
					<td style="text-align: right; margin-left:0px; padding-left:0px;">Rp.{{number_format(($item->base_total - $item->discount_amount)/ $item->qty , 0, ',', '.') }}</td>
					<td style="text-align: right;">Rp.{{number_format($item->tax_amount , 0, ',', '.') }} ({{ $item->tax_percent }}%)</td>
					<td style="text-align: right;">Rp.{{number_format($item->base_total , 0, ',', '.') }}</td>
				</tr>
            @empty
            <p>Product is empty</p>
            @endforelse

				<tr class="total">
					<td colspan="5"></td>
               <td><strong>Total  :</strong></td>
					<td style="text-align: right;"><strong>Rp.{{number_format($orders->grand_total , 0, ',', '.') }}</strong></td>
				</tr>
         </table>
		</div>
	</body>
</html>