@extends('admin.layouts.app')

@section('content')
    <div class="row">
        <!-- İstatistik Kartları -->
        <div class="col-lg-4 col-md-6">
            <div class="card shadow-lg border-0 rounded-lg text-white" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                <div class="card-body text-center">
                    <h5 class="fw-bold">Toplam Proje</h5>
                    <h2 class="display-4 fw-bold">{{ $totalProjects }}</h2>
                    <i class="bi bi-building fs-1"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="card shadow-lg border-0 rounded-lg text-white" style="background: linear-gradient(135deg, #23a6d5, #23d5ab);">
                <div class="card-body text-center">
                    <h5 class="fw-bold">Toplam Haber</h5>
                    <h2 class="display-4 fw-bold">{{ $totalNews }}</h2>
                    <i class="bi bi-newspaper fs-1"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-12">
            <div class="card shadow-lg border-0 rounded-lg text-white" style="background: linear-gradient(135deg, #ff9a9e, #fad0c4);">
                <div class="card-body text-center">
                    <h5 class="fw-bold">Toplam Makine</h5>
                    <h2 class="display-4 fw-bold">{{ $totalMachines }}</h2>
                    <i class="bi bi-gear-wide-connected fs-1"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafikler -->
    <div class="row mt-4">
        <div class="col-lg-6 d-flex">
            <div class="card shadow-sm border-0 h-100 w-100 d-flex flex-column">
                <div class="card-header text-white fw-bold"
                     style="background: linear-gradient(135deg, #A8E6CF, #DCEDC1);">
                    Proje Durumları
                </div>
                <div class="card-body d-flex flex-grow-1 justify-content-center align-items-center bg-white">
                    <canvas id="projectStatusChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-6 d-flex">
            <div class="card shadow-sm border-0 h-100 w-100 d-flex flex-column">
                <div class="card-header text-white fw-bold"
                     style="background: linear-gradient(135deg, #FFABAB, #FFC3A0);">
                    Proje Zaman Grafiği
                </div>
                <div class="card-body d-flex flex-column flex-grow-1 justify-content-center align-items-center bg-white">
                    <canvas id="projectTimelineChart" style="height: 400px; width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Son Eklenenler -->
    <div class="row mt-4">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0"
                 style="background: linear-gradient(135deg, #D4A5A5, #F2D1D1);">
                <div class="card-header text-white fw-bold"
                     style="background: rgba(0, 0, 0, 0.2);">Son Eklenen Projeler</div>
                <div class="card-body mt-4"  >
                    <ul class="list-group list-group-flush">
                        @foreach($latestProjects as $project)
                            <li class="list-group-item d-flex justify-content-between align-items-center"
                                style="background: rgba(255, 255, 255, 0.2);">
                                <span >{{ $project->name }}</span>
                                <small  class="text-muted">{{ $project->created_at->format('d.m.Y') }}</small>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm border-0"
                 style="background: linear-gradient(135deg, #B5EAD7, #C7CEEA);">
                <div class="card-header text-white fw-bold"
                     style="background: rgba(0, 0, 0, 0.2);">Son Eklenen Haberler</div>
                <div class="card-body mt-4">
                    <ul class="list-group list-group-flush">
                        @foreach($latestNews as $news)
                            <li class="list-group-item d-flex justify-content-between align-items-center"
                                style="background: rgba(255, 255, 255, 0.2);">
                                <span>{{ $news->title }}</span>
                                <small class="text-muted">{{ $news->created_at->format('d.m.Y') }}</small>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {

            // RASTGELE RENK OLUŞTURMA FONKSİYONU
            function getRandomColor() {
                return `rgba(${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, 0.8)`;
            }

            // Pie grafikte her dilime farklı renk atamak için
            let pieColors = ['Devam Eden', 'Tamamlanan'].map(() => getRandomColor());

            // 📌 PROJE DURUM GRAFİĞİ (PIE)
            var ctx1 = document.getElementById('projectStatusChart').getContext('2d');
            new Chart(ctx1, {
                type: 'pie',
                data: {
                    labels: ['Devam Eden', 'Tamamlanan'],
                    datasets: [{
                        data: [{{ $ongoingProjects }}, {{ $completedProjects }}],
                        backgroundColor: pieColors // Her dilime rastgele renk atandı
                    }]
                }
            });

            // 📌 PROJE ZAMAN GRAFİĞİ (BAR)
            var ctx2 = document.getElementById('projectTimelineChart').getContext('2d');

            @if($latestProjects->count() > 0)
            let projectTitles = {!! json_encode($latestProjects->pluck('name')->toArray()) !!};
            let projectDates = {!! json_encode($latestProjects->pluck('created_at')->map(fn($date) => $date->format('Y-m'))->toArray()) !!};

            // Bar grafikte her çubuğa farklı renk atamak için
            let barColors = projectTitles.map(() => getRandomColor());

            new Chart(ctx2, {
                type: 'bar',
                data: {
                    labels: projectTitles,
                    datasets: [{
                        label: 'Proje Ekleme Tarihi',
                        data: projectDates.map(date => new Date(date + "-01").getTime()),
                        backgroundColor: barColors // Her çubuğa farklı renk atanıyor
                    }]
                },
                options: {
                    scales: {
                        x: {
                            title: { display: true, text: 'Projeler' }
                        },
                        y: {
                            title: { display: true, text: 'Ay / Yıl' },
                            ticks: {
                                callback: function(value) {
                                    let date = new Date(value);
                                    return date.toLocaleDateString('tr-TR', { year: 'numeric', month: 'long' });
                                }
                            }
                        }
                    }
                }
            });
            @else
            console.warn("Proje Zaman Grafiği için yeterli veri yok.");
            @endif

        });
    </script>


@endsection
