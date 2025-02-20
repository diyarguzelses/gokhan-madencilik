@php use Illuminate\Support\Str; @endphp
@extends('front.layout.app')

@section('content')

    <div class="page-title aos-init aos-animate" data-aos="fade">
        <div class="heading">
            <div class="container">
                <div class="row d-flex justify-content-start ">
                    <div class="col-lg-6">
                        @if($status ==0)
                            <h1>TAMAMLANAN PROJELERİMİZ</h1>
                        @else
                            <h1>DEVAM EDEN PROJELERİMİZ</h1>
                        @endif
                        <p class="mb-0">Çetin İnşaat, modern teknolojiyle müşteri beklentilerini kalite-fiyat dengesiyle
                            karşılar ve yenilikçi, kaliteli hizmeti hedefler.</p>
                    </div>
                </div>
            </div>
        </div>
        <nav class="breadcrumbs">
            <div class="container">
                <ol>
                    <li><a href="{{route('homePage.index')}}">Ana Sayfa</a></li>
                    @if($status ==0)
                        <li><a href="{{route('completedProjects.index')}}" class="current">Tamamlanan Projeler</a></li>
                    @else
                        <li><a href="{{route('continuingProjects.index')}}" class="current">Devam Eden Projeler</a></li>
                    @endif
                </ol>
            </div>
        </nav>
    </div>



    <section id="courses" class="courses section">
        <div class="container">
            <div class="row">
                @foreach($projects as $project)
                    <div class="col-lg-4 col-md-6 d-flex align-items-stretch aos-init aos-animate mt-4"
                         data-aos="zoom-in" data-aos-delay="100">
                        <a  href="{{route('project.detail', $project->slug)}}">
                            <div class="course-item" style="min-height: 450px; display: flex; flex-direction: column;">
                                <div style="height: 300px; overflow: hidden;">
                                    @if($project->images->isNotEmpty())
                                        <img src="{{ asset($project->images->first()->image_path) }}"
                                             onerror="this.onerror=null; this.src='{{asset('front/assets/img/default-img.png')}}';"
                                             class="img-fluid" alt="{{ $project->name }}"
                                             style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <img src="{{ asset('front/assets/img/default-image.png') }}"
                                             onerror="this.onerror=null; this.src='{{asset('front/assets/img/default-img.png')}}';"
                                             class="img-fluid" alt="Varsayılan Resim"
                                             style="width: 100%; height: 100%; object-fit: cover;">
                                    @endif
                                </div>
                                <div class="course-content"
                                     style="flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
                                    <div>
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <p class="price">{{ Str::limit($project->name, 30) }}</p>
                                        </div>
                                    </div>
                                    <div class="trainer d-flex justify-content-start align-items-center">
                                        <div class="trainer-profile d-flex align-items-center">
                                            <a href="#" class="trainer-link">
                                                <i class="fa-solid fa-city"></i> {{ $project->category->name ?? 'Genel Proje' }}
                                            </a>
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

