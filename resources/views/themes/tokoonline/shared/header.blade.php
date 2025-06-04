<nav class="navbar navbar-expand-lg bg-white fixed-top py-4 shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="index.html">Hige<span>Toko</span></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <div class="input-group mx-auto mt-5 mt-lg-0">
                <input type="text" class="form-control" placeholder="Mau cari apa?" aria-label="Mau cari apa?"
                    aria-describedby="button-addon2">
                <button class="btn btn-outline-warning" type="button" id="button-addon2"><i
                        class="bx bx-search"></i></button>
            </div>
            <ul class="navbar-nav ms-auto mt-3 mt-sm-0">
                <li class="nav-item me-3">
                    <a class="nav-link" href="{{ route('carts.index') }}">
                        <i class="bx bx-cart-alt"></i>
                        <span class="badge text-bg-warning rounded-circle position-absolute">3</span>
                    </a>
                </li>
                <li class="nav-item me-5">
                    <a class="nav-link active" href="{{ route('orders.index') }}">
                        <i class="bx bx-shopping-bag"></i>
                        <span class="badge text-bg-warning rounded-circle position-absolute">2</span>
                    </a>
                </li>
                <!-- mobile menu -->
                <div class="dropdown mt-3 d-lg-none">
                    <button class="btn btn-warning dropdown-toggle" type="button" id="dropdownMenuButton1"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Menu
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        <li><a class="dropdown-item" href="index.html">Home</a></li>
                        <li><a class="dropdown-item" href="products.html">Best Seller</a></li>
                        <li><a class="dropdown-item" href="products.html">New Arrival</a></li>
                        <li><a class="dropdown-item" href="products.html">Blog</a></li>
                    </ul>
                </div>
                @auth
                    <div class="dropdown">
                    <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                        Welcome Back
                    </button>
                    <ul class="dropdown-menu my-0" aria-labelledby="dropdownMenuButton1">
                        <li><a class="dropdown-item" href="#">Profile</a></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" style="margin-bottom: 0%">
                                @csrf
                                <button type="submit" class="dropdown-item">Logout</button>
                            </form>
                        </li>
                    </ul>
                    </div>
                @endauth
                @guest
                <ul class="navbar-nav ms-auto mt-3 mx-2 mt-sm-0">
                <li class="nav-item mt-3 mt-lg-0 text-center">
                    <a class="nav-link btn-first" href="#">Login</a>
                </li>
                </ul>
                    <a class="nav-link btn-first" href="#">Register</a>
                </li>
                </ul>
                @endguest
            </ul>
        </div>
    </div>
</nav>
