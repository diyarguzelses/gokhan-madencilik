@extends('front.layout.app')

@section('content')

    <div class="page-title aos-init aos-animate" data-aos="fade">
        <div class="heading">
            <div class="container">
                <div class="row d-flex justify-content-center text-center">
                    <div class="col-lg-8">
                        <h1>İletişim</h1>
                        <p class="mb-0">Bizimle iletişim kurmaya mı çalışıyorsunuz?</p>
                    </div>
                </div>
            </div>
        </div>
        <nav class="breadcrumbs">
            <div class="container">
                <ol>
                    <li><a href="index.html">Ana Sayfa</a></li>
                    <li class="current">İletişim</li>
                </ol>
            </div>
        </nav>
    </div>

    <section id="contact" class="contact section">



        <div class="container aos-init aos-animate mt-5" data-aos="fade-up" data-aos-delay="100">

            <div class="row gy-4">

                <div class="col-lg-4">
                    <div class="info-item d-flex aos-init aos-animate" data-aos="fade-up" data-aos-delay="300">
                        <i class="bi bi-geo-alt flex-shrink-0"></i>
                        <div>
                            <h3>Adres</h3>
                            <p>
                                Kızılırmak Mah dumlu Pınar bulvarı <br>
                                Next Level A Blok3/39 ANKARA</p>
                        </div>
                    </div><!-- End Info Item -->

                    <div class="info-item d-flex aos-init aos-animate" data-aos="fade-up" data-aos-delay="400">
                        <i class="bi bi-telephone flex-shrink-0"></i>
                        <div>
                            <h3>Bizi Arayın</h3>
                            <p>+(90) 312.478 39 13</p>
                        </div>
                    </div><!-- End Info Item -->

                    <div class="info-item d-flex aos-init aos-animate" data-aos="fade-up" data-aos-delay="500">
                        <i class="bi bi-envelope flex-shrink-0"></i>
                        <div>
                            <h3>E-posta</h3>
                            <p>info@cetin-insaat.com.tr</p>
                        </div>
                    </div><!-- End Info Item -->

                </div>

                <div class="col-lg-8">
                    <form action="{{route('communication.sendMessage')}}" method="POST" class="php-email-form aos-init aos-animate" data-aos="fade-up" data-aos-delay="200">
                        @csrf
                        <div class="row gy-4">
                            <div class="col-md-6">
                                <input type="text" name="name" class="form-control" placeholder="Ad Soyad">
                            </div>

                            <div class="col-md-6 ">
                                <input type="email" class="form-control" name="email" placeholder="Eposta">
                            </div>

                            <div class="col-md-12">
                                <textarea class="form-control" name="message" rows="6" placeholder="Mesaj" ></textarea>
                            </div>

                            <div class="col-md-12 text-center">
                                <div class="loading">Loading</div>
                                <div class="error-message"></div>
                                <div class="sent-message">Mesajınız Başarıyla Gönderildi.</div>

                                <button type="submit">Gönder</button>
                            </div>

                        </div>
                    </form>
                </div>

            </div>

        </div>


        <div class="mt-5 aos-init aos-animate" data-aos="fade-up" data-aos-delay="200">
            <iframe style="border:0; width: 100%; height: 300px;" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3060.3533217282024!2d32.810201875831666!3d39.91110867152534!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14d34e44431b677d%3A0x9ef3e1957b025555!2sNext%20Level!5e0!3m2!1sen!2sus!4v1738013975243!5m2!1sen!2sus"  frameborder="0"   allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </section>

@endsection

