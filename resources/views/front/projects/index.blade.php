@extends('front.layout.app')

@section('content')


        <div class="page-title aos-init aos-animate" data-aos="fade" >
            <div class="heading" >
                <div class="container">
                    <div class="row d-flex justify-content-start ">
                        <div class="col-lg-6">
                            <h1>TAMAMLANAN PROJELERİMİZ</h1>
                            <p class="mb-0">Çetin İnşaat, modern teknolojiyle müşteri beklentilerini kalite-fiyat dengesiyle karşılar ve yenilikçi, kaliteli hizmeti hedefler.</p>
                        </div>
                    </div>
                </div>
            </div>
            <nav class="breadcrumbs">
                <div class="container">
                    <ol>
                        <li><a href="{{route('homePage.index')}}">Ana Sayfa</a></li>
                        <li><a href="{{route('completedProjects.index')}}" class="current">Tamamlanan Projeler</a></li>
                    </ol>
                </div>
            </nav>
        </div>



        <section id="courses" class="courses section">

            <div class="container">

                <div class="row">

                    <div class="col-lg-4 col-md-6 d-flex align-items-stretch aos-init aos-animate mt-4" data-aos="zoom-in" data-aos-delay="100">
                          <a href="">
                              <div class="course-item">
                                  <img src="{{asset('front/assets/img/image_19.png')}}" class="img-fluid" alt="...">
                                  <div class="course-content">
                                      <div class="d-flex justify-content-between align-items-center mb-3">
                                          <p class="price">Toki Projemiz</p>
                                          <p class="price2">Elazığ / Merkez</p>
                                      </div>
                                      <p class="description">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus aspernatur autem delectus dignissimos dolorum explicabo fugiat ipsum laborum nostrum omnis repellat, repudiandae unde! Architecto consequatur et ex ipsam natus voluptates.</p>
                                      <div class="trainer d-flex justify-content-start align-items-center">
                                          <div class="trainer-profile d-flex align-items-center">
                                              <img src="assets/img/trainers/trainer-1-2.jpg" class="img-fluid" alt="">
                                              <a href="" class="trainer-link"> <i class="fa-solid fa-city"></i> Altyapı - Üstyapı İnşaatları</a>
                                          </div>

                                      </div>
                                  </div>
                              </div>
                          </a>
                      </div>


                        @foreach($projects as $project)
                            <div class="col-lg-4 col-md-6 d-flex align-items-stretch aos-init aos-animate mt-4" data-aos="zoom-in" data-aos-delay="100">
                                <a href="#">
                                    <div class="course-item">
                                        <img src="{{ asset($projects_img->first()) }}" class="img-fluid" alt="...">
                                        <div class="course-content">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <p class="price">{{ $project->name }}</p>
                                                <p class="price2">Elazığ / Merkez</p>
                                            </div>
                                            <p class="description">{{ \Illuminate\Support\Str::limit($project->description, 240) }}</p>
                                            <div class="trainer d-flex justify-content-start align-items-center">
                                                <div class="trainer-profile d-flex align-items-center">
                                                    <img src="{{ asset('assets/img/trainers/trainer-1-2.jpg') }}" class="img-fluid" alt="">
                                                    <a href="#" class="trainer-link"> <i class="fa-solid fa-city"></i> {{ $project->category->name }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach




                </div>

            </div>

        </section>




{{--      <div class="container">--}}
{{--          <div class="skw-pages mb-5 mt-5">--}}
{{--              <div class="skw-page skw-page-1 active">--}}
{{--                  <div class="skw-page__half skw-page__half--left">--}}
{{--                      <div class="skw-page__skewed">--}}
{{--                          <div class="skw-page__content"></div>--}}
{{--                      </div>--}}
{{--                  </div>--}}
{{--                  <div class="skw-page__half skw-page__half--right">--}}
{{--                      <div class="skw-page__skewed">--}}
{{--                          <div class="skw-page__content">--}}
{{--                              <h2 class="skw-page__heading">Toki İnşşatımız</h2>--}}
{{--                              <p class="skw-page__description">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam fugit, ipsa molestiae necessitatibus quaerat sapiente veniam.</p>--}}
{{--                          </div>--}}
{{--                      </div>--}}
{{--                  </div>--}}
{{--              </div>--}}
{{--              <div class="skw-page skw-page-2">--}}
{{--                  <div class="skw-page__half skw-page__half--left">--}}
{{--                      <div class="skw-page__skewed">--}}
{{--                          <div class="skw-page__content">--}}
{{--                              <h2 class="skw-page__heading">Petrol </h2>--}}
{{--                              <p class="skw-page__description">Nothing to do here, continue scrolling.</p>--}}
{{--                          </div>--}}
{{--                      </div>--}}
{{--                  </div>--}}
{{--                  <div class="skw-page__half skw-page__half--right">--}}
{{--                      <div class="skw-page__skewed">--}}
{{--                          <div class="skw-page__content"></div>--}}
{{--                      </div>--}}
{{--                  </div>--}}
{{--              </div>--}}

{{--              <div class="skw-page skw-page-3 active">--}}
{{--                  <div class="skw-page__half skw-page__half--left">--}}
{{--                      <div class="skw-page__skewed">--}}
{{--                          <div class="skw-page__content"></div>--}}
{{--                      </div>--}}
{{--                  </div>--}}
{{--                  <div class="skw-page__half skw-page__half--right">--}}
{{--                      <div class="skw-page__skewed">--}}
{{--                          <div class="skw-page__content">--}}
{{--                              <h2 class="skw-page__heading">Toki İnşşatımız</h2>--}}
{{--                              <p class="skw-page__description">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam fugit, ipsa molestiae necessitatibus quaerat sapiente veniam.</p>--}}
{{--                          </div>--}}
{{--                      </div>--}}
{{--                  </div>--}}
{{--              </div>--}}
{{--              <div class="skw-page skw-page-4">--}}
{{--                  <div class="skw-page__half skw-page__half--left">--}}
{{--                      <div class="skw-page__skewed">--}}
{{--                          <div class="skw-page__content">--}}
{{--                              <h2 class="skw-page__heading">Petrol </h2>--}}
{{--                              <p class="skw-page__description">Nothing to do here, continue scrolling.</p>--}}
{{--                          </div>--}}
{{--                      </div>--}}
{{--                  </div>--}}
{{--                  <div class="skw-page__half skw-page__half--right">--}}
{{--                      <div class="skw-page__skewed">--}}
{{--                          <div class="skw-page__content"></div>--}}
{{--                      </div>--}}
{{--                  </div>--}}
{{--              </div>--}}


{{--              <div class="navigation-buttons">--}}
{{--                  <button id="prevBtn"><i class="fa-solid fa-chevron-left"></i></button>--}}
{{--                  <button id="nextBtn"><i class="fa-solid fa-chevron-right"></i></button>--}}
{{--              </div>--}}
{{--          </div>--}}
{{--      </div>--}}
{{--        <script>--}}
{{--    $(document).ready(function() {--}}
{{--        var curPage = 1;--}}
{{--        var numOfPages = $(".skw-page").length;--}}
{{--        var animTime = 1000;--}}
{{--        var scrolling = false;--}}
{{--        var pgPrefix = ".skw-page-";--}}

{{--        function pagination() {--}}
{{--            scrolling = true;--}}
{{--            $(pgPrefix + curPage).removeClass("inactive").addClass("active");--}}
{{--            $(pgPrefix + (curPage - 1)).addClass("inactive");--}}
{{--            $(pgPrefix + (curPage + 1)).removeClass("active");--}}

{{--            setTimeout(function() {--}}
{{--                scrolling = false;--}}
{{--            }, animTime);--}}
{{--        }--}}


{{--        $("#prevBtn").on("click", function() {--}}
{{--            if (curPage === 1) return;--}}
{{--            curPage--;--}}
{{--            pagination();--}}
{{--        });--}}


{{--        $("#nextBtn").on("click", function() {--}}
{{--            if (curPage === numOfPages) return;--}}
{{--            curPage++;--}}
{{--            pagination();--}}
{{--        });--}}
{{--    });--}}


{{--</script>--}}
@endsection

