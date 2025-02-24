@extends('front.layout.app')

@section('content')
    <div class="page-title aos-init aos-animate" data-aos="fade">
        <div class="heading">
            <div class="container">
                <div class="row d-flex justify-content-start">
                    <div class="col-lg-5">
                        <h1>Haberler</h1>
                        <p class="mb-0">Çetin İnşaat, modern teknolojiyle müşteri beklentilerini kalite-fiyat dengesiyle karşılar ve yenilikçi, kaliteli hizmeti hedefler.</p>
                    </div>
                </div>
            </div>
        </div>
        <nav class="breadcrumbs">
            <div class="container">
                <ol>
                    <li><a href="{{ url('/') }}">Ana Sayfa</a></li>
                    <li class="current">Haberler</li>
                </ol>
            </div>
        </nav>
    </div>

    <section class="container my-4">
        <div class="row">
{{--            <!-- Sol Kolon: Kapak Fotoğrafı ve Ek Görseller -->--}}
{{--            <div class="col-md-6">--}}
{{--                <!-- Kapak Fotoğrafı -->--}}
{{--                <div class="swiper mySwiper d-flex align-items-center justify-content-start">--}}
{{--                    <div class="swiper-wrapper">--}}
{{--                        <div class="swiper-slide">--}}
{{--                            <img src="{{ asset('/uploads/news/'.$news->image) }}" onerror="this.onerror=null; this.src='{{ asset('front/assets/img/default-img.png') }}';" class="img-fluid" alt="">--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <br>--}}
{{--                <!-- Ek Haber Görselleri (Swiper Slider) -->--}}
{{--                @if($news->images->isNotEmpty())--}}
{{--                    <div class="swiper mySwiper" style="max-height: 300px;">--}}
{{--                        <div class="swiper-wrapper">--}}
{{--                            @foreach($news->images as $img)--}}
{{--                                <div class="swiper-slide">--}}
{{--                                    <img src="{{ asset('uploads/news/'.$img->image) }}" onerror="this.onerror=null; this.src='{{ asset('front/assets/img/default-img.png') }}';" alt="Ek Haber Resmi" class="img-fluid" style="max-width: 100%; height: auto;">--}}
{{--                                </div>--}}
{{--                            @endforeach--}}
{{--                        </div>--}}
{{--                        <div class="swiper-pagination"></div>--}}
{{--                        <div class="swiper-button-next"></div>--}}
{{--                        <div class="swiper-button-prev"></div>--}}
{{--                    </div>--}}
{{--                @endif--}}
{{--            </div>--}}
            <div class="col-md-6">
                <div class="swiper mySwiper d-flex align-items-center justify-content-start">
                    <div class="swiper-wrapper">
                        @if(isset($news) && $news->images->isNotEmpty())
                            @foreach($news->images as $img)
                                <div class="swiper-slide">
                                    <img src="{{ asset('uploads/news/'.$img->image) }}"
                                         onerror="this.onerror=null; this.src='{{ asset('front/assets/img/default-img.png') }}';"
                                         alt="Ek Haber Resmi"
                                         class="img-fluid"
                                         style="max-width: 100%; height: auto;">
                                </div>
                            @endforeach
                        @else
                            <div class="swiper-slide">
                                <img src="{{ asset('front/assets/img/default-img.png') }}"
                                     alt="Varsayılan Resim"
                                     class="img-fluid"
                                     style="max-width: 100%; height: auto;">
                            </div>
                        @endif



                    </div>
                </div>
            </div>
            <!-- Sağ Kolon: Haber İçeriği -->
            <div class="col-md-6 order-2 order-md-1 content aos-init aos-animate" data-aos="fade-up" data-aos-delay="200">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h2 style="color: var(--accent-color)">
                        <a href="{{ route('news.detail', $news->slug) }}" style="text-decoration: none; color: inherit;">
                            {{ $news->title }}
                        </a>
                    </h2>
                    <div class="date">
                        <span class="day">{{ $news->created_at->format('d') }}</span>
                        <span class="month">{{ $news->created_at->locale('tr')->isoFormat('MMM') }}</span>
                    </div>
                </div>
                <p class="fst-italic">
                    <a href="{{ route('news.detail', $news->slug) }}" style="text-decoration: none; color: inherit;">
                        {!! $news->content !!}
                    </a>
                </p>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <!-- Swiper CSS/JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>

    <style>
        /* Modern navigation button tasarımı */
        .swiper-button-next,
        .swiper-button-prev {
            background-color: rgba(0,0,0,0.5);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
        }
        .swiper-button-next::after,
        .swiper-button-prev::after {
            font-size: 20px;
        }
        .swiper-button-next:hover,
        .swiper-button-prev:hover {
            background-color: rgba(0,0,0,0.7);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function(){
            var swiper = new Swiper('.mySwiper', {
                slidesPerView: 1,
                spaceBetween: 10,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
            });
        });
    </script>
@endsection
