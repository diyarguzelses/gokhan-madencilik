@extends('front.layout.app')

@section('content')

    <div class="page-title page-title_2 aos-init aos-animate" data-aos="fade" >
        <div class="heading" >
            <div class="container">
                <div class="row d-flex justify-content-start ">
                    <div class="col-lg-5">
                        <h1>Makina Parkı</h1>
                        <p class="mb-0">Çetin İnşaat, modern teknolojiyle müşteri beklentilerini kalite-fiyat dengesiyle karşılar ve yenilikçi, kaliteli hizmeti hedefler.</p>
                    </div>
                </div>
            </div>
        </div>
        <nav class="breadcrumbs">
            <div class="container">
                <ol>
                    <li><a href="{{route('homePage.index')}}">Ana Sayfa</a></li>
                    <li class="current">Makina Parkı</li>
                </ol>
            </div>
        </nav>
    </div>


    <section class="machine_park">
        <div class="cards-list container">

            <div class="row">
                @foreach($machinePark as $mp)
                    <div class="col-lg-3 col-sm-6 col-md-4">
                        <div class="card">
                            <div class="card_image ">
                                <img src="{{asset($mp->image)}}" onerror="this.onerror=null; this.src='{{asset('front/assets/img/default-img.png')}}';" class="rounded-2" />
                            </div>
                            <div class="card_title">
                                <p>{{$mp->name}}</p>
                            </div>
{{--                            <div class="card_title2">--}}
{{--                                <p>Adet : {{$mp->quantity}}</p>--}}
{{--                            </div>--}}
                        </div>
                    </div>

                @endforeach



            </div>







        </div>
    </section>




@endsection

