@extends('front.layout.app')

@section('content')

    <section id="hero" class="hero section dark-background">

        <img src="{{asset('front/assets/img/bg_1.png')}}" alt="" data-aos="fade-in" class="aos-init aos-animate">

        <div class="container">
            <h2 data-aos="fade-up" data-aos-delay="100" class="aos-init aos-animate">Geçmişten Günümüze<br>Yükselen Başarılar</h2>
            <div class="d-flex mt-4 aos-init aos-animate" data-aos="fade-up" data-aos-delay="300">
                <a href="courses.html" class="btn-get-started">Devamını Gör</a>
            </div>
        </div>

    </section>
    <div id="trainers-index" class="trainers-index">

        <div class="container member_container">

            <div class="row member_row">

                <div class="col-lg-3 col-md-6 d-flex aos-init aos-animate" data-aos="fade-up" data-aos-delay="100">
                    <div class="member">
                        <img src="{{asset('front/assets/img/img_1.png')}}" class="img-fluid" alt="">
                        <div class="member-content">
                            <h4>İnşaat Taahhüt</h4>
                            <hr>
                            <p>
                                Çetin İnşaat departmanının amacı, estetik, doğa dostu, teknolojik ve uzun ömürlü çözümlerle müşterilerimizin inşaat ihtiyaçlarını karşılamaktır. <a href=""><i class="fa-solid fa-link"></i></a>
                            </p>

                        </div>
                    </div>
                </div><!-- End Team Member -->
                <div class="col-lg-3 col-md-6 d-flex aos-init aos-animate" data-aos="fade-up" data-aos-delay="100">
                    <div class="member">
                        <img src="{{asset('front/assets/img/img_2.png')}}" class="img-fluid" alt="">
                        <div class="member-content">
                            <h4>İnşaat Taahhüt</h4>
                            <hr>
                            <p>
                                Çetin İnşaat departmanının amacı, estetik, doğa dostu, teknolojik ve uzun ömürlü çözümlerle müşterilerimizin inşaat ihtiyaçlarını karşılamaktır. <a href=""><i class="fa-solid fa-link"></i></a>
                            </p>

                        </div>
                    </div>
                </div><!-- End Team Member -->
                <div class="col-lg-3 col-md-6 d-flex aos-init aos-animate" data-aos="fade-up" data-aos-delay="100">
                    <div class="member">
                        <img src="{{asset('front/assets/img/img_3.png')}}" class="img-fluid" alt="">
                        <div class="member-content">
                            <h4>İnşaat Taahhüt</h4>
                            <hr>
                            <p>
                                Çetin İnşaat departmanının amacı, estetik, doğa dostu, teknolojik ve uzun ömürlü çözümlerle müşterilerimizin inşaat ihtiyaçlarını karşılamaktır. <a href=""><i class="fa-solid fa-link"></i></a>
                            </p>

                        </div>
                    </div>
                </div><!-- End Team Member -->

            </div>

        </div>

    </div>

    <section id="about" class="about section about_bg">

        <div class="container">

            <div class="row gy-4">
                <div style="padding-bottom: 100px">
                    <h2 style="color: var(--accent-color)">Rakamlarla Çetinler</h2>
                    <hr class="hr">
                </div>

                <div class="col-lg-6 order-1 order-lg-1 aos-init aos-animate" data-aos="fade-up" data-aos-delay="100">
                    <div style="width: 500px;height: 150px;background:var(--accent-color);margin-top: 125px">
                        <h1 class="text-center" style="color: white;padding-top: 50px;">Birlikte Üretiyoruz</h1>
                    </div>
                </div>

                <div class="col-lg-6 order-2 order-lg-2 content aos-init aos-animate" data-aos="fade-up" data-aos-delay="200">
                    <h3 style="color: var(--accent-color)">1990 <br> yılından bu yanabüyüyen güç</h3>
                    <ul class="istatistic">
                        <li><i class="fa-solid fa-circle fa-sm"></i><h4>7 <br> Enerji Santrali</h4></li>
                        <li><i class="fa-solid fa-circle fa-sm"></i><h4>7 <br> Enerji Santrali</h4></li>
                        <li><i class="fa-solid fa-circle fa-sm"></i><h4>7 <br> Enerji Santrali</h4></li>
                        <li><i class="fa-solid fa-circle fa-sm"></i><h4>7 <br> Enerji Santrali</h4></li>
                    </ul>
                    <a href="#" class="read-more"><span>Read More</span><i class="bi bi-arrow-right"></i></a>
                </div>

            </div>

        </div>

    </section>
@endsection
