@extends('admin.layouts.app')
<style>
    .color-input {
        width: 100%;
        height: 60px; /* Daha büyük ve modern görünüm */
        border-radius: 10px;
        border: 1px solid #ddd;
        transition: border-color 0.3s ease;
    }

    .color-input:focus {
        border-color: #2563eb;
        outline: none;
    }

    .palette-card {
        cursor: pointer;
        border-radius: 10px;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .palette-card:hover {
        transform: scale(1.05);
        box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.2);
    }

    .palette-header,
    .palette-sidebar,
    .palette-button,
    .palette-bg {
        width: 100%;
    }
</style>

@section('content')
    <div class="card mb-5">
        <div class="card-header" style="background: linear-gradient(135deg, #1e3c72, #2a5298); color: white;">
            <span class="mb-0">Renk Ayar Yönetimi</span>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.settings.update') }}" method="POST" id="settingsForm">
                @csrf
                @method('PUT')

                <div class="row gy-4">
                    <!-- Mevcut renk alanları -->
                    <div class="col-md-6">
                        <label for="navbar_color" class="form-label fw-bold">Navbar Rengi</label>
                        <input type="color" class="form-control color-input" id="navbar_color" name="navbar_color"
                               value="{{ $settings->where('key', 'navbar_color')->first()->value ?? '#34495e' }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="sidebar_color" class="form-label fw-bold">Sidebar Rengi</label>
                        <input type="color" class="form-control color-input" id="sidebar_color" name="sidebar_color"
                               value="{{ $settings->where('key', 'sidebar_color')->first()->value ?? '#2c3e50' }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="button_color" class="form-label fw-bold">Buton Rengi</label>
                        <input type="color" class="form-control color-input" id="button_color" name="button_color"
                               value="{{ $settings->where('key', 'button_color')->first()->value ?? '#e74c3c' }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="background_color" class="form-label fw-bold">Arka Plan Rengi</label>
                        <input type="color" class="form-control color-input" id="background_color" name="background_color"
                               value="{{ $settings->where('key', 'background_color')->first()->value ?? '#ecf0f1' }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="navbar_text_color" class="form-label fw-bold">Navbar Yazı Rengi</label>
                        <input type="color" class="form-control color-input" id="navbar_text_color" name="navbar_text_color"
                               value="{{ $settings->where('key', 'navbar_text_color')->first()->value ?? '#ffffff' }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="text_color" class="form-label fw-bold">Genel Yazı Rengi</label>
                        <input type="color" class="form-control color-input" id="text_color" name="text_color"
                               value="{{ $settings->where('key', 'text_color')->first()->value ?? '#2c3e50' }}" required>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary px-4 py-6">Kaydet</button>
                </div>
            </form>

            <hr class="my-5">

            <!-- Modern Renk Paletleri -->
            <h5 class="mb-3">Hazır Renk Paletleri</h5>
            <div class="row g-3" id="colorPalettes">
                <!-- Her palet, modern sitelerde tercih edilen renk kombinasyonlarını içerir:
                     [ navbar_color, sidebar_color, button_color, background_color, navbar_text_color, text_color ] -->
                @foreach([
                    ['#34495e', '#2c3e50', '#e74c3c', '#ecf0f1', '#ffffff', '#2c3e50'],
                    ['#2ecc71', '#27ae60', '#f1c40f', '#ffffff', '#ffffff', '#34495e'],
                    ['#8e44ad', '#71368a', '#e67e22', '#fdfefe', '#ffffff', '#2c3e50'],
                    ['#3498db', '#2980b9', '#e74c3c', '#f5f6fa', '#ffffff', '#2d3436']
                ] as $palette)
                    <div class="col-md-3">
                        <div class="card shadow-sm border-0 palette-card" data-palette="{{ json_encode($palette) }}">
                            <div class="palette-header" style="height: 50px; background: {{ $palette[0] }};"></div>
                            <div class="palette-sidebar" style="height: 50px; background: {{ $palette[1] }};"></div>
                            <div class="palette-button" style="height: 50px; background: {{ $palette[2] }};"></div>
                            <div class="palette-bg" style="height: 50px; background: {{ $palette[3] }};"></div>
                            <div class="palette-navbar-text" style="height: 50px; background: {{ $palette[4] }};"></div>
                            <div class="palette-text" style="height: 50px; background: {{ $palette[5] }};"></div>
                            <div class="text-center py-2 fw-bold">Paleti Kullan</div>
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

            // Hazır paletlerden renk seçme
            palettes.forEach(palette => {
                palette.addEventListener('click', function () {
                    const colors = JSON.parse(this.dataset.palette);
                    document.getElementById('navbar_color').value = colors[0];
                    document.getElementById('sidebar_color').value = colors[1];
                    document.getElementById('button_color').value = colors[2];
                    document.getElementById('background_color').value = colors[3];
                    document.getElementById('navbar_text_color').value = colors[4];
                    document.getElementById('text_color').value = colors[5];
                    Swal.fire({
                        title: 'Palet Uygulandı!',
                        html: 'Palet başarıyla renk alanlarına uygulandı.<br><br>Kaydet butonuna bastığınızda renkler uygulanacaktır.',
                        icon: 'success',
                        confirmButtonText: 'Tamam'
                    });
                });
            });

            // Form gönderimi
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                const formData = new FormData(form);

                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Başarılı!',
                                text: 'Renk ayarları başarıyla güncellendi.',
                                icon: 'success',
                                confirmButtonText: 'Tamam'
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Hata!',
                                text: 'Ayarlar kaydedilirken bir hata oluştu.',
                                icon: 'error',
                                confirmButtonText: 'Tamam'
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            title: 'Hata!',
                            text: 'Bir sorun oluştu. Lütfen tekrar deneyin.',
                            icon: 'error',
                            confirmButtonText: 'Tamam'
                        });
                        console.error(error);
                    });
            });
        });
    </script>
@endsection

