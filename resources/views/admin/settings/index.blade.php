@extends('admin.layouts.app')

@section('content')

    {{-- Çalışan Sayısı Yönetimi --}}
    <div class="card shadow-sm border-0 mb-5 rounded-4">
        <div class="card-header text-white rounded-top-4 d-flex align-items-center" style="background: linear-gradient(135deg, #1e3c72, #2a5298);">
            <i class="fas fa-users fa-lg me-3"></i>
            <h5 class="fw-bold mb-0">Çalışan Sayısı Yönetimi</h5>
        </div>
        <div class="card-body py-5 px-4 bg-light rounded-bottom-4">
            <form action="{{ route('admin.settings.personnel.update') }}" method="POST" id="personnelForm" class="needs-validation" novalidate>
                @csrf
                @method('PUT')
                <div class="row g-4 align-items-end">
                    <div class="col-md-9">
                        <label for="personnel_count" class="form-label fw-semibold text-secondary">Toplam Çalışan Sayısı</label>
                        <div class="input-group input-group-lg shadow-sm">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-user-friends text-primary"></i>
                        </span>
                            <input type="number"
                                   class="form-control border-start-0"
                                   id="personnel_count"
                                   name="personnel_count"
                                   value="{{ $settings->where('key', 'personnel_count')->first()->value ?? '1' }}"
                                   min="0"
                                   placeholder="Örn: 125"
                                   required>
                        </div>
                    </div>
                    <div class="col-md-3 d-grid">
                        <button type="submit" class="btn btn-lg text-white shadow-sm"
                                style="background: linear-gradient(135deg, #1e3c72, #2a5298);">
                            <i class="fas fa-check-circle me-2"></i>Güncelle
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    {{-- Renk Ayarı Açıklaması --}}
    <div class="alert alert-primary rounded-4 shadow-sm">
        <h5 class="fw-bold mb-2"><i class="fas fa-palette me-2"></i>Renk Ayar Yönetimi Hakkında</h5>
        <p>Bu panel, web sitesinin renk şemasını özelleştirmek için kullanılır. Adminler navbar, butonlar, arka plan ve yazı rengi gibi alanları düzenleyebilir.</p>
        <ul class="mb-0">
            <li><strong>Navbar Rengi:</strong> Üst menü arka planı</li>
            <li><strong>Buton Rengi:</strong> Sayfa içi buton arka planı</li>
            <li><strong>Arka Plan Rengi:</strong> Genel sayfa zemini</li>
            <li><strong>Genel Yazı Rengi:</strong> Metin içerikleri</li>
        </ul>
    </div>

    {{-- Renk Ayarları Formu --}}
    <div class="card mb-5 shadow-sm border-0 rounded-4">
        <div class="card-header text-white rounded-top-4" style="background: linear-gradient(135deg, #1e3c72, #2a5298);">
            <h5 class="fw-bold mb-0">
                <i class="fas fa-sliders-h me-2"></i>Renk Ayar Yönetimi
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.settings.update') }}" method="POST" id="settingsForm">
                @csrf
                @method('PUT')

                <div class="row gy-4 mt-2">
                    @php
                        $colorInputs = [
                            ['id' => 'navbar_color', 'label' => 'Navbar Rengi', 'default' => '#34495e'],
                            ['id' => 'button_color', 'label' => 'Buton Rengi', 'default' => '#e74c3c'],
                            ['id' => 'background_color', 'label' => 'Arka Plan Rengi', 'default' => '#ecf0f1'],
                            ['id' => 'text_color', 'label' => 'Genel Yazı Rengi', 'default' => '#2c3e50']
                        ];
                    @endphp

                    @foreach($colorInputs as $input)
                        <div class="col-md-6">
                            <label for="{{ $input['id'] }}" class="form-label fw-bolder">{{ $input['label'] }}</label>
                            <input type="color"
                                   class="form-control form-control-lg color-input"
                                   id="{{ $input['id'] }}"
                                   name="{{ $input['id'] }}"
                                   value="{{ $settings->where('key', $input['id'])->first()->value ?? $input['default'] }}"
                                   required>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary btn-lg px-4 rounded-3 shadow-sm">
                        <i class="fas fa-save me-2"></i>Kaydet
                    </button>
                </div>
            </form>

            <hr class="my-5">

            {{-- Hazır Renk Paletleri --}}
            <h5 class="fw-bold mb-3"><i class="fas fa-th-large me-2"></i>Hazır Renk Paletleri</h5>
            <div class="row g-3" id="colorPalettes">
                @foreach([
                    ['#000000', '#003da6', '#ffffff', '#000000'],
                    ['#34495e', '#e74c3c', '#ecf0f1', '#2c3e50'],
                    ['#3498db', '#e74c3c', '#f5f6fa', '#2d3436'],
                    ['#1abc9c', '#16a085', '#ecf0f1', '#2c3e50'],
                ] as $palette)
                    <div class="col-md-3">
                        <div class="card shadow-sm border-0 palette-card" data-palette="{{ json_encode($palette) }}">
                            <div class="palette-preview d-flex flex-column">
                                @foreach($palette as $color)
                                    <div style="height: 50px; background: {{ $color }};"></div>
                                @endforeach
                            </div>
                            <div class="text-center py-2 fw-bold palette-apply">Paleti Kullan</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('settingsForm');
            const palettes = document.querySelectorAll('.palette-card');

            palettes.forEach(palette => {
                palette.addEventListener('click', function () {
                    const colors = JSON.parse(this.dataset.palette);

                    document.getElementById('navbar_color').value = colors[0];
                    document.getElementById('button_color').value = colors[1];
                    document.getElementById('background_color').value = colors[2];
                    document.getElementById('text_color').value = colors[3];

                    Swal.fire({
                        title: 'Renk Paleti Seçildi',
                        text: 'Yeni renkler uygulandı! Kaydet butonuna basarak değişiklikleri kaydedebilirsiniz.',
                        icon: 'success',
                        confirmButtonText: 'Tamam'
                    });
                });
            });

            form.addEventListener('submit', function (e) {
                e.preventDefault();
                const formData = new FormData(form);

                fetch(form.action, {
                    method: 'POST',
                    body: formData
                })
                    .then(res => res.json())
                    .then(data => {
                        Swal.fire({
                            title: data.success ? 'Başarılı!' : 'Hata!',
                            text: data.success ? 'Renk ayarları başarıyla güncellendi.' : 'Ayarlar kaydedilirken bir hata oluştu.',
                            icon: data.success ? 'success' : 'error',
                            confirmButtonText: 'Tamam'
                        }).then(() => data.success && window.location.reload());
                    })
                    .catch(err => {
                        Swal.fire({
                            title: 'Hata!',
                            text: 'Bir sorun oluştu. Lütfen tekrar deneyin.',
                            icon: 'error',
                            confirmButtonText: 'Tamam'
                        });
                        console.error(err);
                    });
            });

            const personnelForm = document.getElementById('personnelForm');
            if (personnelForm) {
                personnelForm.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const formData = new FormData(personnelForm);

                    fetch(personnelForm.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                        .then(res => res.json())
                        .then(data => {
                            Swal.fire({
                                title: data.success ? 'Başarılı!' : 'Hata!',
                                text: data.success ? 'Çalışan sayısı başarıyla güncellendi.' : 'Bir sorun oluştu.',
                                icon: data.success ? 'success' : 'error',
                                confirmButtonText: 'Tamam'
                            });
                        })
                        .catch(err => {
                            Swal.fire({
                                title: 'Hata!',
                                text: 'Sunucu hatası oluştu.',
                                icon: 'error',
                                confirmButtonText: 'Tamam'
                            });
                            console.error(err);
                        });
                });
            }
        });
    </script>
@endsection
