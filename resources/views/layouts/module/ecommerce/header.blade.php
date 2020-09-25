<header class="header_area">
    <div class="top_menu row m0">
        <div class="container-fluid">
            <div class="float-left">
                <p>Call Us: 012 44 5698 7456 896</p>
            </div>
            <div class="float-right">
                <ul class="right_side">
                    @if (auth()->guard('customer')->check())
                      <li><a href="{{ route('customer.logout') }}">Logout</a></li>
                    @else
                      <li><a href="{{ route('customer.login') }}">Login</a></li>
                    @endif
                    <li><a href="{{ route('customer.dashboard') }}">My Account</a></li>
                    <li><a href="contact.html">Contact Us</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="main_menu">
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <a class="navbar-brand logo_h" href="{{ url('/') }}">
                    <img src="{{ asset('ecommerce/img/gabutspeople-logo-tp.png') }}" height="50px" alt="">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                 aria-expanded="false" aria-label="Toggle navigation">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse offset" id="navbarSupportedContent">
                    <div class="row w-100">
                        <div class="col-lg-7 pr-0">
                            @include('layouts.module.ecommerce.menu')
                        </div>

                        <div class="col-lg-5">
                            <ul class="nav navbar-nav navbar-right right_nav pull-right">
                                <hr>
                                <li class="nav-item">
                                    <a href="#" class="icons">
                                        <i class="fa fa-search" aria-hidden="true"></i>
                                    </a>
                                </li>
                                <hr>
                                <li class="nav-item">
                                    <a href="#" class="icons">
                                        <i class="fa fa-user" aria-hidden="true"></i>
                                    </a>
                                </li>
                                <hr>
                                <li class="nav-item">
                                    <a href="#" class="icons">
                                        <i class="fa fa-heart-o" aria-hidden="true"></i>
                                    </a>
                                </li>
                                <hr>
                                <li class="nav-item">
                                    <a href="{{ route('front.list_cart') }}" class="icons">
                                        <i class="lnr lnr lnr-cart"></i>
                                    </a>
                                </li>
                                <hr>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</header>