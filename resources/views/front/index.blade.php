@php use Illuminate\Support\Str; @endphp
@extends('front.layout.app')

@section('content')

    <section id="hero" class="hero section dark-background">

        <img src="{{asset('front/assets/img/bg_1.png')}}" alt="" data-aos="fade-in" class="aos-init aos-animate">

        <div class="container">
            <h2 data-aos="fade-up" data-aos-delay="100" class="aos-init aos-animate">Geçmişten Günümüze<br>Yükselen
                Başarılar</h2>
{{--            <div class="d-flex mt-4 aos-init aos-animate" data-aos="fade-up" data-aos-delay="300">--}}
{{--                <a href="courses.html" class="btn-get-started">Devamını Gör</a>--}}
{{--            </div>--}}
        </div>

    </section>
    <div id="trainers-index" class="trainers-index">

        <div class="container member_container">

            <div class="row member_row">

                @foreach($firstThreeSectors as $sector)
                    <div class="col-lg-3 col-md-6 d-flex aos-init aos-animate" data-aos="fade-up" data-aos-delay="100">
                        <div class="member">
                            <img src="{{asset('uploads/sectors/'.$sector->image)}}" onerror="this.onerror=null; this.src='{{asset('front/assets/img/default-img.png')}}';" class="img-fluid" alt="">
                            <div class="member-content">
                                <h4>{{$sector->name}}</h4>
                                <hr>
                                <p>
                                    {!! Str::limit($sector->text, 100) !!}

                                </p>

                            </div>
                        </div>
                    </div><!-- End Team Member -->

                @endforeach
            </div>

        </div>

    </div>

    <section id="about" class="about section about_bg">

        <div class="container">

            <div class="row gy-4">
                <div class="hr_baslik">
                    <h2 style="color: var(--accent-color)">Rakamlarla Gökhan Madencilik</h2>
                    <hr class="hr">
                </div>

                <div class="col-lg-6 order-1 order-lg-1 aos-init aos-animate" data-aos="fade-up" data-aos-delay="100">
                    <div class="block">
                        <h1 class="text-center" style="color: white;padding-top: 50px;">Birlikte Üretiyoruz</h1>
                    </div>
                </div>

                <div class="col-lg-6 order-2 order-lg-2 content aos-init aos-animate" data-aos="fade-up"
                     data-aos-delay="200">
                    <h3 style="color: var(--accent-color)">1990 <br> yılından bu yana büyüyen güç</h3>
                    <ul class="istatistic ">
                        <ul class="row">
                            <li class="col-lg-6">
                                <i class="fa-solid fa-circle fa-sm"></i>
                                <h4>{{ $projectCount }} <br> Projeler</h4>
                            </li>
                            <li class="col-lg-6">
                                <i class="fa-solid fa-circle fa-sm"></i>
                                <h4>{{ $sectorCount }} <br> Sektör Alanı</h4>
                            </li>
                            <li class="col-lg-6">
                                <i class="fa-solid fa-circle fa-sm"></i>
                                <h4>{{ $machineCount }}+ <br> Makine Sayısı</h4>
                            </li><li class="col-lg-6">
                                <i class="fa-solid fa-circle fa-sm"></i>
                                <h4>{{ $projeCategoryCount }}+ <br> Faaliyet Alanı</h4>
                            </li>
                        </ul>

                    </ul>

                </div>

            </div>

        </div>

    </section>

    <section id="about-us" class="section about-us">

        <div class="">
            <div class="container hr_baslik">
                <h2 style="color: var(--accent-color)">Haberler</h2>
                <hr class="hr">
            </div>
            <div class="row gy-4">
                <div class="col-lg-4 order-1 order-lg-1 aos-init aos-animate" data-aos="fade-up" data-aos-delay="100">
                    @if(!empty($lastnew->image) && file_exists(public_path('uploads/news/'.$lastnew->image)))
                        <img src="{{ asset('/uploads/news/'.$lastnew->image) }}" class="img-fluid" style="height: 300px!important;object-fit: cover" alt="">
                    @else
                        <img src="{{ asset('front/assets/img/default-img.png') }}" class="img-fluid" style="height: 300px!important;object-fit: cover" alt="">
                    @endif
                </div>


                <div class="col-lg-8 order-2 order-lg-2 content aos-init aos-animate" data-aos="fade-up" data-aos-delay="200">
                    @if(isset($lastnew) && $lastnew->title)
                        <a href="{{ route('news.detail', $lastnew->slug) }}" style="color: black!important;">
                            <h3 style="color: var(--accent-color)">{{ $lastnew->title }}</h3>
                            <div class="fsitalict- w-75 bg-white2 mr-2">
                                {!! Str::limit(strip_tags($lastnew->content), 450) !!}
                                <a href="{{ route('news.detail', $lastnew->slug) }}" style="color: var(--accent-color)"> Devamını gör</a>

                            </div>
                        </a>
                    @endif
                </div>



            </div>

        </div>
        <div class="news_title">
            <div class="container">
                <div id="testimonials" class="testimonials" style="padding-top: 100px">
                    <div class="container aos-init aos-animate" data-aos="fade-up" data-aos-delay="100">
                        <div class="swiper init-swiper swiper-initialized swiper-horizontal swiper-backface-hidden">
                            <script type="application/json" class="swiper-config">
                                {
                                  "loop": true,
                                  "speed": 600,
                                  "autoplay": {
                                    "delay": 5000
                                  },
                                  "slidesPerView": "auto",
                                  "pagination": {
                                    "el": ".swiper-pagination",
                                    "type": "bullets",
                                    "clickable": true
                                  },
                                  "breakpoints": {
                                    "320": {
                                      "slidesPerView": 1,
                                      "spaceBetween": 40
                                    },
                                    "1200": {
                                      "slidesPerView": 2,
                                      "spaceBetween": 20
                                    }
                                  }
                                }
                            </script>

                            <div class="swiper-wrapper">
                                @foreach ($news as $index => $new)
                                    <div class="swiper-slide">
                                        <a href="{{ route('news.detail', $new->slug) }}">
                                            <div class="testimonial-wrap">
                                                <div class="testimonial-item">
                                                    <img src="{{ asset('/uploads/news/'.$new->image) }}"
                                                         onerror="this.onerror=null; this.src='{{ asset('front/assets/img/default-img.png') }}';"
                                                         class="testimonial-img" alt="Haber Resmi">
                                                    <h3>{{ Str::limit(($new->title), 70) }}</h3>
                                                    <p>
                                                        <i class="bi bi-quote quote-icon-left"></i>
                                                        <span>{{ Str::limit(strip_tags($new->content), 200) }}</span>
                                                        <i class="bi bi-quote quote-icon-right"></i>
                                                    </p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>

                            <div class="swiper-pagination swiper-pagination-clickable swiper-pagination-bullets swiper-pagination-horizontal">
                                @foreach ($news as $index => $new)
                                    <span class="swiper-pagination-bullet {{ $loop->first ? 'swiper-pagination-bullet-active' : '' }}" tabindex="0" role="button" aria-label="Go to slide {{ $loop->iteration }}"></span>
                                @endforeach
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>


    </section>



    <section class="game-section">
        <div class="container hr_baslik">
            <h2 style="color: var(--accent-color)">Faaliyet Alanları</h2>
            <hr class="hr">
        </div>

        <div class="owl-carousel custom-carousel owl-theme">
            @foreach($nextFourSectors as $sector)
                <div class="item" style="background-image: url('/uploads/sectors/{{ $sector->image }}');cursor: auto" onerror="this.onerror=null; this.src='{{asset('front/assets/img/default-img.png')}}';">
                    <div class="item-desc">
                        <h4>{{ $sector->name }}</h4>
                    </div>
                </div>
            @endforeach
        </div>



    </section>






    <script>


        $(".custom-carousel").owlCarousel({
            autoWidth: true,
            loop: true
        });

    </script>



@endsection

