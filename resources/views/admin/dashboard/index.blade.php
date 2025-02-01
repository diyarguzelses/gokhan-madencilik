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
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @foreach($latestProjects as $project)
                            <li class="list-group-item d-flex justify-content-between align-items-center"
                                style="background: rgba(255, 255, 255, 0.2);">
                                <span>{{ $project->name }}</span>
                                <small class="text-muted">{{ $project->created_at->format('d.m.Y') }}</small>
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
                <div class="card-body">
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
        // Proje Durum Grafiği
        var ctx1 = document.getElementById('projectStatusChart').getContext('2d');
        new Chart(ctx1, {
            type: 'pie',
            data: {
                labels: ['Devam Eden', 'Tamamlanan'],
                datasets: [{
                    data: [{{ $ongoingProjects }}, {{ $completedProjects }}],
                    backgroundColor: ['#FF9800', '#4CAF50']
                }]
            }
        });

        // Proje Zaman Grafiği (Son 5 proje ekleme tarihi)
        var ctx2 = document.getElementById('projectTimelineChart').getContext('2d');
        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: {!! json_encode($latestProjects->pluck('title')) !!},
                datasets: [{
                    label: 'Proje Ekleme Tarihi',
                    data: {!! json_encode($latestProjects->pluck('created_at')->map(fn($date) => $date->timestamp)) !!},
                    backgroundColor: '#3f51b5'
                }]
            },
            options: {
                scales: {
                    x: { title: { display: true, text: 'Projeler' } },
                    y: { title: { display: true, text: 'Tarih' }, ticks: { callback: function(value) { return new Date(value * 1000).toLocaleDateString(); } } }
                }
            }
        });
    </script>
@endsection
