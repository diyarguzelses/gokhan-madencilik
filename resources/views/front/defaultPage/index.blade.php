@extends('front.layout.app')

@section('content')

    <div class="page-title page-title_2 aos-init aos-animate" data-aos="fade" >
        <div class="heading" >
            <div class="container">
                <div class="row d-flex justify-content-start ">
                    <div class="col-lg-5">
                        <h1>İNŞAAT & TAAHHÜT</h1>
                        <p class="mb-0">Çetin İnşaat, modern teknolojiyle müşteri beklentilerini kalite-fiyat dengesiyle karşılar ve yenilikçi, kaliteli hizmeti hedefler.</p>
                    </div>
                </div>
            </div>
        </div>
        <nav class="breadcrumbs">
            <div class="container">
                <ol>
                    <li><a href="index.html">Ana Sayfa</a></li>
                    <li class="current">İnşaat & Taahhüt</li>
                </ol>
            </div>
        </nav>
    </div>

    <section id="about-us" class="section about-us">

        <div class="container">

            <div class="row gy-4">

                <div class="col-lg-5 order-1 order-lg-2 aos-init aos-animate default_img2" data-aos="fade-up" data-aos-delay="100">
                    <img src="{{asset('front/assets/img/image_19.png')}}" class="img-fluid" alt="" style="border-right: 6px solid var(--accent-color);margin-bottom: 30px">
                    <img src="{{asset('front/assets/img/image_19.png')}}" class="img-fluid" alt="" style="border-right: 6px solid var(--accent-color);">
                </div>

                <div class="col-lg-6 order-2 order-lg-1 content aos-init aos-animate" data-aos="fade-up" data-aos-delay="200">
                    <h3>Kırk Yıllık Tecrübe</h3>
                    <p class="fst-italic">
                        Çetin İnşaat, inşaat sektöründeki deneyimi ve geniş hizmet yelpazesiyle, müşterilerinin her türlü ihtiyacını karşılamayı hedefleyen bir anlayışla çalışmalarını sürdürmektedir.
                        Proje-keşif ve teknik şartname oluşturma hizmetlerinden başlayarak, anahtar teslim mimari ve iç mimari projelere kadar tüm süreçlerde modern teknoloji ve yenilikçi yaklaşımlarla estetik çözümler üretmektedir.<br><br>


                        Ayrıca restorasyon, renovasyon ve tadilat hizmetleriyle eski yapıların değerini artırmakta; peyzaj hizmetleriyle çevreye estetik dokunuşlar katmaktadır.
                        Çetin İnşaat, müşteri beklentilerini modern teknolojiyle birleştirerek kalite-fiyat dengesini gözetir ve her zaman toplam kaliteyi hedefler. İnsanlarla yaşayan, doğaya duyarlı ve çevreye estetik değer katan projeler üretmek, temel misyonlarından biridir. Teknolojik, çağdaş, konforlu ve ekonomik ömrü uzun çözümlerle müşterilerine sürdürülebilir değerler sunmayı ilke edinmiştir.
                    </p>
                </div>

            </div>
            <div class="row gy-4 mt-3">

                <div class="col-lg-5 order-1 order-lg-1 aos-init aos-animate default_img" data-aos="fade-up" data-aos-delay="100" >
                    <img src="{{asset('front/assets/img/image_19.png')}}" class="img-fluid" alt="" style="border-right: 6px solid var(--accent-color);margin-bottom: 30px">
                    <img src="{{asset('front/assets/img/image_19.png')}}" class="img-fluid" alt="" style="border-right: 6px solid var(--accent-color);">
                </div>

                <div class="col-lg-6 order-2 order-lg-2 content aos-init aos-animate" data-aos="fade-up" data-aos-delay="200">
                    <p class="fst-italic">
                        Hizmet alanlarımız arasında lüks villalardan çok katlı konut ve iş merkezlerine, sanayi tesislerinden site projelerine kadar geniş bir yelpaze bulunmaktadır.
                        Özel mekanlar için; restoran, kafe, bar, butik, otel, mağaza ve spor salonu gibi projelerde yenilikçi ve özgün tasarımlar sunulmaktadır. Cephe, iç mimari, teknik proje uygulamaları ile
                        birlikte ısı, havalandırma, yalıtım, mekanik ve elektrik tesisat sistemleri,
                        yangın uyarı ve söndürme sistemleri, güvenlik sistemleri gibi kritik altyapı çalışmalarında uzman kadrosuyla çözüm odaklı yaklaşımlar sergilenmektedir.<br>
                        <br><br>

                        İç mimari tasarımlar ve dekorasyon uygulamalarında estetik ve işlevselliği birleştirerek, yaşam alanlarını daha kullanışlı ve göz alıcı hale getirmeyi hedefliyoruz. Peyzaj projelerinde doğaya duyarlı ve çevresel uyumlu yaklaşımlarla estetik tasarımlar oluşturuyoruz.
                        Çetin İnşaat, yılların tecrübesiyle inşaat sektöründe güvenin simgesi haline gelmiştir. Müşterilerimiz için yalnızca binalar değil, aynı zamanda yaşam alanları tasarlıyor ve her projeye değer katmayı amaçlıyoruz. Teknoloji, estetik ve sürdürülebilirliği birleştirerek geleceğin yapılarında iz bırakmaya devam ediyoruz.

                    </p>
                </div>

            </div>
        </div>

    </section>






@endsection

