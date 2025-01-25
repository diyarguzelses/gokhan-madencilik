<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Index - Mentor Bootstrap Template</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- Favicons -->
    <link href="{{asset('front/assets/img/favicon.png')}}" rel="icon">
    <link href="{{asset('front/assets/img/apple-touch-icon.png')}}" rel="apple-touch-icon">

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

    <!-- =======================================================
    * Template Name: Mentor
    * Template URL: https://bootstrapmade.com/mentor-free-education-bootstrap-theme/
    * Updated: Aug 07 2024 with Bootstrap v5.3.3
    * Author: BootstrapMade.com
    * License: https://bootstrapmade.com/license/
    ======================================================== -->
</head>

<body class="index-page">

<header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">



        <nav id="navmenu" class="navmenu    ">
            <ul>
                <li><a href="index.html" class="active">Ana Sayfa<br></a></li>
                <li class="dropdown"><a href="#"><span>Kurumsal</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                    <ul>
                        <li><a href="#">Dropdown 1</a></li>
                        <li class="dropdown"><a href="#"><span>Deep Dropdown</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                            <ul>
                                <li><a href="#">Deep Dropdown 1</a></li>
                                <li><a href="#">Deep Dropdown 2</a></li>
                                <li><a href="#">Deep Dropdown 3</a></li>
                                <li><a href="#">Deep Dropdown 4</a></li>
                                <li><a href="#">Deep Dropdown 5</a></li>
                            </ul>
                        </li>
                        <li><a href="#">Dropdown 2</a></li>
                        <li><a href="#">Dropdown 3</a></li>
                        <li><a href="#">Dropdown 4</a></li>
                    </ul>
                </li>
                <li class="dropdown"><a href="#"><span>Projeler</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                    <ul>
                        <li><a href="#">Dropdown 1</a></li>
                        <li class="dropdown"><a href="#"><span>Deep Dropdown</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                            <ul>
                                <li><a href="#">Deep Dropdown 1</a></li>
                                <li><a href="#">Deep Dropdown 2</a></li>
                                <li><a href="#">Deep Dropdown 3</a></li>
                                <li><a href="#">Deep Dropdown 4</a></li>
                                <li><a href="#">Deep Dropdown 5</a></li>
                            </ul>
                        </li>
                        <li><a href="#">Dropdown 2</a></li>
                        <li><a href="#">Dropdown 3</a></li>
                        <li><a href="#">Dropdown 4</a></li>
                    </ul>
                </li>

            </ul>

        </nav>
        <a href="index.html" class="logo d-flex align-items-center justify-content-center ">
            <!-- Uncomment the line below if you also wish to use an image logo -->
             <img src="{{asset('front/assets/img/logo.png')}}" class="" style="" alt="">
{{--            <h1 class="sitename pt-5">Mentor</h1>--}}
        </a>
        <nav id="navmenu" class="navmenu ">
            <ul>
                <li class="dropdown"><a href="#"><span>Faaliyet Alanları</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                    <ul>
                        <li><a href="#">Dropdown 1</a></li>
                        <li class="dropdown"><a href="#"><span>Deep Dropdown</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                            <ul>
                                <li><a href="#">Deep Dropdown 1</a></li>
                                <li><a href="#">Deep Dropdown 2</a></li>
                                <li><a href="#">Deep Dropdown 3</a></li>
                                <li><a href="#">Deep Dropdown 4</a></li>
                                <li><a href="#">Deep Dropdown 5</a></li>
                            </ul>
                        </li>
                        <li><a href="#">Dropdown 2</a></li>
                        <li><a href="#">Dropdown 3</a></li>
                        <li><a href="#">Dropdown 4</a></li>
                    </ul>
                </li>
                <li><a href="about.html">Haberler</a></li>
                <li><a href="courses.html">İletişim</a></li>

            </ul>
            <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>



    </div>
</header>

<main class="main">

    @yield('content')

</main>

<footer id="footer" class="footer position-relative light-background">

    <div class="container footer-top">
        <div class="row gy-4">
            <div class="col-lg-4 col-md-6 footer-about">
                <a href="index.html" class=" " >
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

            <div class="col-lg-2 col-md-3 footer-links">
                <h4>Kurumsal</h4>
                <ul>
                    <li><a href="#"><i class="fa-solid fa-angle-right"></i>Hakkımızda</a></li>
                    <li><a href="#"><i class="fa-solid fa-angle-right"></i>Makina Parkı</a></li>
                    <li><a href="#"><i class="fa-solid fa-angle-right"></i>Şirket Kurucumuz</a></li>

                </ul>
            </div>

            <div class="col-lg-2 col-md-3 footer-links">
                <h4>Projeler</h4>
                <ul>
                    <li><a href="#"><i class="fa-solid fa-angle-right"></i>Devam Eden Projeler</a></li>
                    <li><a href="#"><i class="fa-solid fa-angle-right"></i>Tamamlanan Projeler</a></li>
                </ul>
            </div>
            <div class="col-lg-2 col-md-3 footer-links">
                <h4>Faaliyet Alanları</h4>
                <ul>
                    <li><a href="#"><i class="fa-solid fa-angle-right"></i>İnşaat Taahhüt</a></li>
                    <li><a href="#"><i class="fa-solid fa-angle-right"></i>Petrol Sektörü </a></li>
                    <li><a href="#"><i class="fa-solid fa-angle-right"></i>Toplu Konut Projeleri </a></li>
                    <li><a href="#"><i class="fa-solid fa-angle-right"></i>Beton Ve Asfalt İmalatı</a></li>
                    <li><a href="#"><i class="fa-solid fa-angle-right"></i> Yol Köprü ve Viyadük Yapımı</a></li>
                    <li><a href="#"><i class="fa-solid fa-angle-right"></i>Maden Enerji</a></li>
                    <li><a href="#"><i class="fa-solid fa-angle-right"></i>Harfiyat Hizmetleri</a></li>
                </ul>
            </div>
            <div class="col-lg-2 col-md-3 footer-links">
                <h4>İletişim</h4>
                <ul>
                    <li><a href="#"><i class="fa-solid fa-location-dot"></i>Kızılırmak Mah. <br>Dumlupınar Bulvarı Next Level A Blok -3/39 ANKARA</a></li>
                    <li><a href="#"><i class="fa-solid fa-phone"></i>+(90) 312 478 39 13 </a></li>
                    <li><a href="#"><i class="fa-solid fa-envelope"></i>info@cetin-insaat.com.tr </a></li>
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
