<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        :root {
            --background-color: {{ config('app.settings.background_color', '#ffffff') }} !important;
            --default-color: {{ config('app.settings.default_color', '#444444') }} !important;
            --heading-color: {{ config('app.settings.heading_color', '#37423b') }} !important;
            --accent-color: {{ config('app.settings.button_color', '#004CA6') }} !important;
            --surface-color: {{ config('app.settings.surface_color', '#ffffff') }} !important;
            --contrast-color: {{ config('app.settings.contrast_color', '#ffffff') }} !important;

            --nav-color: {{ config('app.settings.navbar_color', '#272828') }} !important;
            --nav-hover-color: {{ config('app.settings.button_color', '#004CA6') }} !important;
            --nav-mobile-background-color: {{ config('app.settings.background_color', '#ffffff') }} !important;
            --nav-dropdown-background-color: {{ config('app.settings.background_color', '#ffffff') }} !important;
            --nav-dropdown-color: {{ config('app.settings.navbar_color', '#272828') }} !important;
            --nav-dropdown-hover-color: {{ config('app.settings.button_color', '#004CA6') }} !important;
        }
    </style>

    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Çetiner İnşaat</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- Favicons -->
    <link href="{{asset('front/assets/img/favicon_1.png')}}" rel="icon">
    <link href="{{asset('front/assets/img/favicon_1.png')}}" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{asset('front/assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('front/assets/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
    <link href="{{asset('front/assets/vendor/aos/aos.css')}}" rel="stylesheet">
    <link href="{{asset('front/assets/vendor/glightbox/css/glightbox.min.css')}}" rel="stylesheet">
    <link href="{{asset('front/assets/vendor/swiper/swiper-bundle.min.css')}}" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="{{asset('front/assets/css/main.css')}}" rel="stylesheet">
    <link href="{{asset('front/assets/css/responsive.css')}}" rel="stylesheet">


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />


    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />

    <!-- Swiper JS -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>


    <!-- Owl Carousel CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">

    <!-- Owl Carousel Theme CSS (isteğe bağlı) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Owl Carousel JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <!-- =======================================================
    * Template Name: Mentor
    * Template URL: https://bootstrapmade.com/mentor-free-education-bootstrap-theme/
    * Updated: Aug 07 2024 with Bootstrap v5.3.3
    * Author: BootstrapMade.com
    * License: https://bootstrapmade.com/license/
    ======================================================== -->
</head>

<body class="index-page">

<header id="header" class="header d-flex align-items-center sticky-top" >
    <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

        <a href="{{route('homePage.index')}}" class="logo d-flex align-items-center justify-content-center ">
            <!-- Uncomment the line below if you also wish to use an image logo -->
            <img src="{{asset('front/assets/img/logo.png')}}" class="" style="" alt="">
            {{--            <h1 class="sitename pt-5">Mentor</h1>--}}
        </a>
        <nav id="navmenu" class="navmenu    ">
            <ul>
                @foreach($menus as $menu)
                    <li class="{{ $menu->children->count() > 0 ? 'dropdown' : '' }}">
                        <a href="{{ $menu->url ?? '#' }}" >
                            <span>{{ $menu->name }}</span>
                            @if($menu->children->count() > 0)
                                <i class="bi bi-chevron-down toggle-dropdown"></i>
                            @endif
                        </a>
                        @if($menu->children->count() > 0)
                            <ul>
                                @foreach($menu->children as $submenu)
                                    <li class="{{ $submenu->children->count() > 0 ? 'dropdown' : '' }}">
                                        <a href="{{ $submenu->url ?? '#' }}" >
                                            <span>{{ $submenu->name }}</span>
                                            @if($submenu->children->count() > 0)
                                                <i class="bi bi-chevron-down toggle-dropdown"></i>
                                            @endif
                                        </a>
                                        @if($submenu->children->count() > 0)
                                            <ul>
                                                @foreach($submenu->children as $submenu_2)
                                                    <li >
                                                        <a href="{{ $submenu_2->url ?? '#' }}">{{ $submenu_2->name }}</a>
                                                    </li>

                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>

                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach

            </ul>
            <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>




    </div>
</header>

<main class="main">
    @yield('content')
</main>

<footer id="footer" class="footer position-relative light-background">
    <div class="container" >
        <hr style="border: 2px solid var(--accent-color);opacity: 1;">
    </div>
    <div class="container footer-top">
        <div class="row gy-4">
            <div class="col-lg-4 col-md-6 footer-about">
                <a href="{{route('homePage.index')}}" class=" " >
                    <!-- Uncomment the line below if you also wish to use an image logo -->
                    <img src="{{asset('front/assets/img/logo.png')}}" class="" style="" alt="">
                    {{--            <h1 class="sitename pt-5">Mentor</h1>--}}
                </a>
                <div class="social-links d-flex m-4 ">
                    <a href=""><i class="bi bi-twitter-x"></i></a>
                    <a href=""><i class="bi bi-facebook"></i></a>
                    <a href=""><i class="bi bi-instagram"></i></a>
                </div>

            </div>

            @foreach($footer_menu as $menu)
                <div class="col-lg-2 col-md-3 footer-links">
                    <h4>{{ $menu->name }}</h4>
                    <ul>
                        @foreach($menu->children as $submenu)
                            <li><a href="{{ $submenu->url }}"><i class="fa-solid fa-angle-right"></i>{{ $submenu->name }}</a></li>
                        @endforeach
                    </ul>
                </div>
            @endforeach

            <div class="col-lg-2 col-md-3 footer-links">
                <h4>İletişim</h4>
                <ul>
                    <li><a href="#"><i class="fa-solid fa-location-dot"></i>Kızılırmak Mah. <br>Dumlupınar Bulvarı Next Level A Blok -3/39 ANKARA</a></li>
                    <li><i class="fa-solid fa-phone"></i>+(90) 312 478 39 13</li>
                    <li><i class="fa-solid fa-envelope"></i>info@cetin-insaat.com.tr</li>
                </ul>
            </div>





        </div>
    </div>

    <div class="container copyright text-center mt-4">
        <p>© <span>Copyright</span> <strong class="px-1 sitename"><a href="">Çetin İnşaat</a> </strong> <span>Tüm Hakları Saklıdır</span></p>

    </div>

</footer>

<!-- Scroll Top -->
<a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<!-- Preloader -->
<div id="preloader"></div>



<!-- Vendor JS Files -->
<script src="{{asset('front/assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('front/assets/vendor/php-email-form/validate.js')}}"></script>
<script src="{{asset('front/assets/vendor/aos/aos.js')}}"></script>
<script src="{{asset('front/assets/vendor/glightbox/js/glightbox.min.js')}}"></script>
<script src="{{asset('front/assets/vendor/purecounter/purecounter_vanilla.js')}}"></script>
<script src="{{asset('front/assets/vendor/swiper/swiper-bundle.min.js')}}"></script>

<!-- Main JS File -->
<script src="{{asset('front/assets/js/main.js')}}"></script>



</body>

</html>
