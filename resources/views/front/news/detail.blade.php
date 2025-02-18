@extends('front.layout.app')

@section('content')
    <div class="page-title aos-init aos-animate" data-aos="fade" >
        <div class="heading" >
            <div class="container">
                <div class="row d-flex justify-content-start ">
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
                    <li><a href="index.html">Ana Sayfa</a></li>
                    <li class="current">Haberler</li>
                </ol>
            </div>
        </nav>
    </div>

    <section class="container">
        <div class="row">
            <div class="col-6">
                <div class="swiper mySwiper d-flex align-items-center justify-content-start">
                    <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <img src="{{asset('/uploads/news/'.$news->image)}}" onerror="this.onerror=null; this.src='{{asset('front/assets/img/default-img.png')}}';" class="img-fluid" alt="">
                            </div>

                    </div>
                </div>
            </div>
            <div class="col-lg-6 order-2 order-lg-1 content aos-init aos-animate" data-aos="fade-up" data-aos-delay="200">
                    <div class="d-flex align-items-center justify-content-between">
                        <h2 style="color: var(--accent-color)">{{ $news->title }}</h2>
                        <div class="date">
                            <span class="day">{{ $news->created_at->format('d') }}</span>
                            <span class="month">{{ $news->created_at->locale('tr')->isoFormat('MMM') }}</span>
                        </div>
                    </div>
                    <p class="fst-italic">
                        <span>{!! $news->content !!}</span>
                    </p>
            </div>
        </div>
    </section>









@endsection

