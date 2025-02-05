@extends('admin.layouts.app')


@section('content')
    <div class="card mb-5">
        <div class="card-header border-0 pt-4 pb-4 px-4 d-flex align-items-center justify-content-between"
             style="background: linear-gradient(135deg, #1e3c72, #2a5298); color: white; border-radius: 10px 10px 0 0;">
            <div>
                <span class="fw-bold fs-4">Yeni Proje Ekle</span><br>
                <span class="fw-light fs-6">Projenizin detaylarını ve görsellerini buradan ekleyebilirsiniz.</span>
            </div>
        </div>

        <div class="card-body py-3">
            <form action="{{ route('admin.projects.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Proje Adı</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>

                <div class="mb-3">
                    <label for="category_id" class="form-label">Kategori</label>
                    <select class="form-control" id="category_id" name="category_id" required>
                        <option value="">Bir kategori seçin</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Proje Durumu</label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="1">Devam Eden</option>
                        <option value="0">Tamamlanan</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Açıklama</label>
                    <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
                </div>

                <!-- Sürükle Bırak Alanı -->
                <div class="mb-3">
                    <label class="form-label">Proje Görselleri Yükle</label>
                    <div id="imageDropzone" class="border border-dashed text-center p-5" style="cursor: pointer;">
                        <p class="fw-bold">Sürükle ve Bırak veya Tıklayın</p>
                        <p class="text-muted">Birden fazla dosya yükleyebilirsiniz (JPEG, PNG, JPG - Maksimum 2MB).</p>
                    </div>
                    <input type="file" id="images" name="images[]" class="d-none" multiple>
                    <div id="imagePreview" class="d-flex flex-wrap mt-3"></div>
                </div>

                <button type="submit" class="btn btn-success">Kaydet</button>
                <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary">Geri</a>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            const dropzone = $('#imageDropzone');
            const fileInput = $('#images');
            const previewContainer = $('#imagePreview');
            let uploadedFiles = [];

            // Tıklama ile dosya yükleme alanını açma
            dropzone.on('click', function (e) {
                e.preventDefault();
                e.stopPropagation(); // Olayın yayılmasını durdurduk
                fileInput.trigger('click');
            });

            // Dosya seçildiğinde önizleme oluştur
            fileInput.on('change', function (e) {
                const files = e.target.files;
                handleFiles(files);
            });

            // Sürükle ve Bırak işlemleri
            dropzone.on('dragover', function (e) {
                e.preventDefault();
                e.stopPropagation();
                dropzone.addClass('bg-light');
            });

            dropzone.on('dragleave', function (e) {
                e.preventDefault();
                e.stopPropagation();
                dropzone.removeClass('bg-light');
            });

            dropzone.on('drop', function (e) {
                e.preventDefault();
                e.stopPropagation();
                dropzone.removeClass('bg-light');
                const files = e.originalEvent.dataTransfer.files;
                handleFiles(files);
            });

            // Dosyaları işleme ve önizleme
            function handleFiles(files) {
                Array.from(files).forEach(file => {
                    // Dosya tipi ve boyutunu kontrol et
                    if (file.size > 2 * 1024 * 1024) { // 2MB
                        alert('Dosya boyutu 2MB\'ı geçemez: ' + file.name);
                        return;
                    }
                    if (!['image/jpeg', 'image/png', 'image/jpg', 'image/gif'].includes(file.type)) {
                        alert('Geçersiz dosya türü: ' + file.name);
                        return;
                    }

                    uploadedFiles.push(file);
                    const reader = new FileReader();
                    reader.onload = function (event) {
                        const imgHtml = `
                            <div class="me-3 mb-3 position-relative preview-image">
                                <img src="${event.target.result}" alt="Görsel" style="width: 100px; height: 100px; object-fit: cover;">
                                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 delete-temp-image" data-index="${uploadedFiles.length - 1}">&times;</button>
                            </div>`;
                        previewContainer.append(imgHtml);
                    };
                    reader.readAsDataURL(file);
                });

                // Input'u güncelle
                const dataTransfer = new DataTransfer();
                uploadedFiles.forEach(file => dataTransfer.items.add(file));
                fileInput[0].files = dataTransfer.files;
            }

            // Geçici görsel silme
            $(document).on('click', '.delete-temp-image', function () {
                const index = $(this).data('index');
                uploadedFiles.splice(index, 1);
                $(this).closest('.preview-image').remove();

                // Input'u güncelle
                const dataTransfer = new DataTransfer();
                uploadedFiles.forEach(file => dataTransfer.items.add(file));
                fileInput[0].files = dataTransfer.files;

                // Tüm delete-temp-image butonlarının data-index değerlerini güncelle
                $('.delete-temp-image').each(function (i) {
                    $(this).attr('data-index', i);
                });
            });
        });
    </script>
@endsection
