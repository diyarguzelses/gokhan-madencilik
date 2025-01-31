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
                        <li><a href="index.html">Ana Sayfa</a></li>
                        <li class="current">Tamamlanan Projeler</li>
                    </ol>
                </div>
            </nav>
        </div>


      <div class="container">
          <div class="skw-pages mb-5 mt-5">
              <div class="skw-page skw-page-1 active">
                  <div class="skw-page__half skw-page__half--left">
                      <div class="skw-page__skewed">
                          <div class="skw-page__content"></div>
                      </div>
                  </div>
                  <div class="skw-page__half skw-page__half--right">
                      <div class="skw-page__skewed">
                          <div class="skw-page__content">
                              <h2 class="skw-page__heading">Toki İnşşatımız</h2>
                              <p class="skw-page__description">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam fugit, ipsa molestiae necessitatibus quaerat sapiente veniam.</p>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="skw-page skw-page-2">
                  <div class="skw-page__half skw-page__half--left">
                      <div class="skw-page__skewed">
                          <div class="skw-page__content">
                              <h2 class="skw-page__heading">Petrol </h2>
                              <p class="skw-page__description">Nothing to do here, continue scrolling.</p>
                          </div>
                      </div>
                  </div>
                  <div class="skw-page__half skw-page__half--right">
                      <div class="skw-page__skewed">
                          <div class="skw-page__content"></div>
                      </div>
                  </div>
              </div>

              <div class="skw-page skw-page-3 active">
                  <div class="skw-page__half skw-page__half--left">
                      <div class="skw-page__skewed">
                          <div class="skw-page__content"></div>
                      </div>
                  </div>
                  <div class="skw-page__half skw-page__half--right">
                      <div class="skw-page__skewed">
                          <div class="skw-page__content">
                              <h2 class="skw-page__heading">Toki İnşşatımız</h2>
                              <p class="skw-page__description">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam fugit, ipsa molestiae necessitatibus quaerat sapiente veniam.</p>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="skw-page skw-page-4">
                  <div class="skw-page__half skw-page__half--left">
                      <div class="skw-page__skewed">
                          <div class="skw-page__content">
                              <h2 class="skw-page__heading">Petrol </h2>
                              <p class="skw-page__description">Nothing to do here, continue scrolling.</p>
                          </div>
                      </div>
                  </div>
                  <div class="skw-page__half skw-page__half--right">
                      <div class="skw-page__skewed">
                          <div class="skw-page__content"></div>
                      </div>
                  </div>
              </div>


              <div class="navigation-buttons">
                  <button id="prevBtn"><i class="fa-solid fa-chevron-left"></i></button>
                  <button id="nextBtn"><i class="fa-solid fa-chevron-right"></i></button>
              </div>
          </div>
      </div>



<script>
    $(document).ready(function() {
        var curPage = 1;
        var numOfPages = $(".skw-page").length;
        var animTime = 1000;
        var scrolling = false;
        var pgPrefix = ".skw-page-";

        function pagination() {
            scrolling = true;
            $(pgPrefix + curPage).removeClass("inactive").addClass("active");
            $(pgPrefix + (curPage - 1)).addClass("inactive");
            $(pgPrefix + (curPage + 1)).removeClass("active");

            setTimeout(function() {
                scrolling = false;
            }, animTime);
        }


        $("#prevBtn").on("click", function() {
            if (curPage === 1) return;
            curPage--;
            pagination();
        });


        $("#nextBtn").on("click", function() {
            if (curPage === numOfPages) return;
            curPage++;
            pagination();
        });
    });


</script>
@endsection

