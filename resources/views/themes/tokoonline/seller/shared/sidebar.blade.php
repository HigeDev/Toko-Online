<div class="wrapper">
	<nav id="sidebar" class="sidebar js-sidebar">
		<div class="sidebar-content js-simplebar">
			<a class="sidebar-brand" href="index.html">
			<span class="align-middle">AdminKit</span></a>
			<ul class="sidebar-nav">
				<li class="sidebar-header">Pages</li>
				<li class="sidebar-item {{ request()->is('seller') ? 'active' : '' }}">
					<a class="sidebar-link" href="{{ route('seller.index') }}">
					<i class="align-middle" data-feather="sliders"></i> <span class="align-middle">Dashboard</span></a>
				</li>
				<li class="sidebar-item {{ request()->is('seller/products') ? 'active' : '' }}">
					<a class="sidebar-link" href="{{ route('seller.products') }}">
					<i class="align-middle" data-feather="box"></i> <span class="align-middle">Products</span></a>
				</li>
				<li class="sidebar-item {{ request()->is('seller/order*') ? 'active' : '' }}">
					<a class="sidebar-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#ordersMenu" 
						aria-expanded="false">
						 <i class="align-middle" data-feather="shopping-bag"></i> 
						 <span class="align-middle">Orders</span>
						 <i class="align-middle float-end" data-feather="chevron-down"></i> <!-- Arrow -->
					</a>
					<ul id="ordersMenu" class="collapse show list-unstyled">
						 <li>
							  <a class="sidebar-link ms-4 {{ request()->is('seller/order/allOrders') ? 'active' : '' }}" 
								  href="{{ route('seller.allOrders') }}">
									<i class="align-middle" data-feather="clock"></i> Pending Orders
							  </a>
						 </li>
						 <li>
							  <a class="sidebar-link ms-4 {{ request()->is('seller/order/confirmedOrders') ? 'active' : '' }}" 
								  href="{{ route('seller.confirmedOrders') }}">
									<i class="align-middle" data-feather="check-circle"></i> Confirmed Orders
							  </a>
						 </li>
						 <li>
							  <a class="sidebar-link ms-4 {{ request()->is('seller/order/deliveredOrders') ? 'active' : '' }}" 
								  href="{{ route('seller.deliveredOrders') }}">
									<i class="align-middle" data-feather="check-circle"></i> Delivered Orders
							  </a>
						 </li>
					</ul>
			  </li>
			  


				<li class="sidebar-header">Tools & Components</li>
				<li class="sidebar-item">
					<a class="sidebar-link" href="ui-buttons.html">
					<i class="align-middle" data-feather="square"></i> <span class="align-middle">Buttons</span></a>
				</li>
			</ul>
		</div>
	</nav>