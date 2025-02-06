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
            @foreach($news as $new)
                <div class="snip col-4">


                    <div class="image" style="min-height: 300px" ><img src="{{asset('/uploads/news/'.$new->image)}}" alt="pr-sample23" onerror="this.onerror=null; this.src='{{asset('front/assets/img/default-img.png')}}';"/></div>
                    <div class="figcaption" style="width: 100%;">
                        <div class="date"><span class="day">{{ $new->created_at->format('d') }}</span><span class="month">{{ $new->created_at->format('M') }}</span></div>
                        <h3>{{ Str::limit($new->title, 40, '...') }}</h3>
                        <p >
                            {{ Str::limit($new->content, 150, '...') }}
                        </p>
                    </div>
                    <a href="{{route('news.detail', $new->slug)}}"></a>
                </div>
            @endforeach

        </div>


    </section>










@endsection

