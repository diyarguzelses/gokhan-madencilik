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
        <div></div>
        <div class="card-body">
            <form action="{{ route('admin.settings.update') }}" method="POST" id="settingsForm">
                @csrf
                @method('PUT')

                <div class="row gy-4">
                    <div class="col-md-6">
                        <label for="navbar_color" class="form-label fw-bold">Navbar Rengi</label>
                        <input type="color" class="form-control color-input" id="navbar_color" name="navbar_color"
                               value="{{ $settings->where('key', 'navbar_color')->first()->value ?? '#1e3c72' }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="sidebar_color" class="form-label fw-bold">Sidebar Rengi</label>
                        <input type="color" class="form-control color-input" id="sidebar_color" name="sidebar_color"
                               value="{{ $settings->where('key', 'sidebar_color')->first()->value ?? '#2a5298' }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="button_color" class="form-label fw-bold">Buton Rengi</label>
                        <input type="color" class="form-control color-input" id="button_color" name="button_color"
                               value="{{ $settings->where('key', 'button_color')->first()->value ?? '#ff7e5f' }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="background_color" class="form-label fw-bold">Arka Plan Rengi</label>
                        <input type="color" class="form-control color-input" id="background_color" name="background_color"
                               value="{{ $settings->where('key', 'background_color')->first()->value ?? '#ffffff' }}" required>
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
                <!-- Modern Paletler -->
                @foreach([
                    ['#1e293b', '#0f172a', '#2563eb', '#f8fafc'],
                    ['#d97706', '#7c2d12', '#facc15', '#fef3c7'],
                    ['#047857', '#065f46', '#34d399', '#f0fdf4'],
                    ['#1e40af', '#1e3a8a', '#60a5fa', '#f3f4f6']
                ] as $palette)
                    <div class="col-md-3">
                        <div class="card shadow-sm border-0 palette-card" data-palette="{{ json_encode($palette) }}">
                            <div class="palette-header" style="height: 50px; background: {{ $palette[0] }};"></div>
                            <div class="palette-sidebar" style="height: 50px; background: {{ $palette[1] }};"></div>
                            <div class="palette-button" style="height: 50px; background: {{ $palette[2] }};"></div>
                            <div class="palette-bg" style="height: 50px; background: {{ $palette[3] }};"></div>
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
