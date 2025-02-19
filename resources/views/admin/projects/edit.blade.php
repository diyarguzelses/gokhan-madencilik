@extends('admin.layouts.app')

@section('content')
    <div class="card mb-5">
        <div class="card-header border-0 pt-4 pb-4 px-4 d-flex align-items-center justify-content-between"
             style="background: linear-gradient(135deg, #1e3c72, #2a5298); color: white; border-radius: 10px 10px 0 0;">
            <div>
                <span class="fw-bold fs-4">Proje Düzenle</span><br>
                <span class="fw-light fs-6">Projenizin detaylarını buradan güncelleyebilirsiniz.</span>
            </div>
        </div>

        <div class="card-body py-3">
            <form id="projectForm" action="{{ route('admin.projects.update', $project->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <!-- Güncelleme işleminde HTTP method'unu belirtmek için -->
                @method('Post')
                <div class="mb-3">
                    <label for="name" class="form-label">Proje Adı</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $project->name }}" required>
                </div>

                <div class="mb-3">
                    <label for="category_id" class="form-label">Kategori</label>
                    <select class="form-control" id="category_id" name="category_id" required>
                        <option value="">Bir kategori seçin</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ $project->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Açıklama</label>
                    <textarea class="form-control" id="description" name="description" rows="5" required>{{ $project->description }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Proje Durumu</label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="1" {{ old('status', $project->status ?? 1) == 1 ? 'selected' : '' }}>Devam Eden</option>
                        <option value="0" {{ old('status', $project->status ?? 1) == 0 ? 'selected' : '' }}>Tamamlanan</option>
                    </select>
                </div>

                <!-- Sürükle Bırak Alanı -->
                <div class="mb-3">
                    <label class="form-label">Yeni Görseller Yükle</label>
                    <div id="imageDropzone" class="border border-dashed text-center p-5" style="cursor: pointer;">
                        <p class="fw-bold">Sürükle ve Bırak veya Tıklayın</p>
                        <p class="text-muted">Birden fazla dosya yükleyebilirsiniz (JPEG, PNG, JPG - Maksimum 2MB).</p>
                    </div>
                    <!-- Dosya input'unu dropzone dışında taşıdık -->
                    <input type="file" id="images" name="images[]" class="d-none" multiple>
                    <div id="imagePreview" class="d-flex flex-wrap mt-3"></div>
                </div>

                <!-- Mevcut Görseller -->
                <div class="mb-3">
                    <label class="form-label">Mevcut Görseller</label>
                    <div class="d-flex flex-wrap">
                        @foreach ($project->images as $image)
                            <div class="me-3 mb-3 position-relative">
                                <img src="{{ asset($image->image_path) }}" alt="Proje Görseli"
                                     style="width: 100px; height: 100px; object-fit: cover;">
                                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 delete-image"
                                        data-id="{{ $image->id }}">&times;</button>
                            </div>
                        @endforeach
                    </div>
                </div>

                <button type="submit" class="btn btn-success">Güncelle</button>
                <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary">Geri</a>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <!-- CKEditor 5 Classic Editor CDN -->
    <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
    <script>
        $(document).ready(function () {
            // CKEditor 5 Başlatma: Proje açıklaması için
            function MyCustomUploadAdapterPlugin(editor) {
                editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
                    return new MyUploadAdapter(loader);
                };
            }

            class MyUploadAdapter {
                constructor(loader) {
                    this.loader = loader;
                }

                upload() {
                    return this.loader.file
                        .then(file => new Promise((resolve, reject) => {
                            const data = new FormData();
                            data.append('upload', file);

                            fetch('/api/ckeditor/upload', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: data
                            })
                                .then(response => response.json())
                                .then(result => {

                                    resolve({
                                        default: result.url
                                    });
                                })
                                .catch(error => {
                                    reject('Dosya yüklenirken hata oluştu: ' + error);
                                });
                        }));
                }
                abort() {

                }
            }
            ClassicEditor
                .create(document.querySelector('#description'), {
                    extraPlugins: [ MyCustomUploadAdapterPlugin ],

                })
                .then(editor => {
                    window.courseEditor = editor;
                    console.log('Editor custom adapter ile yüklendi.');
                })
                .catch(error => {
                    console.error('CKEditor yüklenirken hata oluştu:', error);
                });

            // Form gönderilmeden önce CKEditor içeriğini textarea'ya aktarıyoruz.
            $('#projectForm').submit(function () {
                $('#description').val(window.projectEditor.getData());
            });

            // Sürükle ve Bırak ile Yeni Görsel Yükleme İşlemleri
            const dropzone = $('#imageDropzone');
            const fileInput = $('#images');
            const previewContainer = $('#imagePreview');
            let uploadedFiles = [];

            // Tıklama ile dosya yükleme alanını açma
            dropzone.on('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                fileInput.trigger('click');
            });

            // Dosya seçildiğinde önizleme oluşturma
            fileInput.on('change', function (e) {
                const files = e.target.files;
                handleFiles(files);
            });

            // Dosyaları işleme ve önizleme oluşturma
            function handleFiles(files) {
                Array.from(files).forEach(file => {
                    if (file.size > 2048 * 1024 || !['image/jpeg', 'image/png', 'image/jpg'].includes(file.type)) {
                        alert('Geçersiz dosya: ' + file.name);
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
            });

            // Mevcut görsel silme
            $(document).on('click', '.delete-image', function () {
                const imageId = $(this).data('id');

                Swal.fire({
                    title: 'Emin misiniz?',
                    text: "Bu görsel kalıcı olarak silinecektir!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Evet, sil!',
                    cancelButtonText: 'Hayır'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `{{ route('admin.projects.deleteImage', ':id') }}`.replace(':id', imageId),
                            method: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function (response) {
                                if (response.success) {
                                    Swal.fire(
                                        'Silindi!',
                                        'Görsel başarıyla silindi.',
                                        'success'
                                    ).then(() => location.reload());
                                } else {
                                    Swal.fire(
                                        'Hata!',
                                        'Görsel silinirken bir sorun oluştu.',
                                        'error'
                                    );
                                }
                            },
                            error: function () {
                                Swal.fire(
                                    'Hata!',
                                    'Bir hata oluştu, lütfen tekrar deneyin.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
