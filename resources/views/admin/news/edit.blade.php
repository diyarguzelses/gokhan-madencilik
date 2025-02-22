@extends('admin.layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3>Haber Düzenle</h3>
            </div>
            <div class="card-body">
                <form id="newsEditForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Başlık -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Başlık</label>
                        <input type="text" name="title" id="title" class="form-control"
                               value="{{ $news->title }}" required>
                    </div>

                    <!-- İçerik -->
                    <div class="mb-3">
                        <label for="content" class="form-label">İçerik</label>
                        <textarea name="content" id="content" class="form-control"
                                  rows="6" required>{{ $news->content }}</textarea>
                    </div>

                    <!-- Kapak Fotoğrafı -->
                    <div class="mb-3">
                        <label class="form-label">Kapak Fotoğrafı</label>
                        @if($news->image)
                            <div id="currentCoverContainer" class="mb-2 position-relative d-inline-block">
                                <img src="{{ asset('uploads/news/' . $news->image) }}"
                                     alt="Kapak Resmi"
                                     style="width: 150px; height: auto;">
                                <button type="button"
                                        class="btn btn-danger btn-sm position-absolute top-0 end-0 delete-cover-image"
                                        data-id="{{ $news->id }}">&times;</button>
                            </div>
                        @endif
                        <input type="file" name="cover_image" id="cover_image"
                               class="form-control" accept="image/*">
                        <img id="coverPreview" src="#" alt="Kapak Önizleme"
                             style="display: none; margin-top: 10px; max-width: 100%; max-height: 200px;">
                    </div>

                    <!-- Ek Haber Görselleri -->
                    <div class="mb-3">
                        <label class="form-label">Ek Haber Görselleri</label>
                        <!-- Sürükle Bırak Alanı -->
                        <div id="imagesDropzone"
                             class="border border-dashed text-center p-5 mb-3"
                             style="cursor: pointer;">
                            <p class="fw-bold">Sürükle ve Bırak veya Tıklayın</p>
                            <p class="text-muted">
                                Birden fazla dosya yükleyebilirsiniz
                                (JPEG, PNG, JPG - Maksimum 2MB).
                            </p>
                        </div>
                        <!-- Gizli Dosya Input -->
                        <input type="file" id="news_images" name="images[]"
                               class="d-none" multiple accept="image/*">

                        <!-- Tek Konteyner: Mevcut + Yeni Görseller -->
                        <div id="imagePreview" class="d-flex flex-wrap">
                            <!-- Mevcut Görseller -->
                            @foreach($news->images as $image)
                                <div class="position-relative me-3 mb-3 preview-image"
                                     data-image-id="{{ $image->id }}">
                                    <img src="{{ asset('uploads/news/' . $image->image) }}"
                                         alt="Haber Resmi"
                                         style="width: 100px; height: 100px; object-fit: cover;">
                                    <button type="button"
                                            class="btn btn-sm btn-danger position-absolute top-0 end-0 delete-existing-image"
                                            data-id="{{ $image->id }}">&times;</button>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Güncelle</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- CKEditor 5 Classic Editor CDN -->
    <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
    <script>
        // CKEditor için custom upload adapter
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
                return this.loader.file.then(file => new Promise((resolve, reject) => {
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
                            resolve({ default: result.url });
                        })
                        .catch(error => {
                            reject('Dosya yüklenirken hata oluştu: ' + error);
                        });
                }));
            }
            abort() {}
        }

        // CKEditor'ü başlat
        ClassicEditor
            .create(document.querySelector('#content'), {
                extraPlugins: [ MyCustomUploadAdapterPlugin ]
            })
            .then(editor => {
                window.newsEditor = editor;
            })
            .catch(error => {
                console.error('CKEditor yüklenirken hata oluştu:', error);
            });
    </script>
    <script>
        $(document).ready(function(){
            /* Kapak Resim Önizleme */
            $('#cover_image').change(function(){
                let file = this.files[0];
                if(file){
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        $('#coverPreview').attr('src', e.target.result).show();
                    };
                    reader.readAsDataURL(file);
                } else {
                    $('#coverPreview').hide();
                }
            });

            /* Kapak Resim Silme İşlemi */
            $(document).on('click', '.delete-cover-image', function(){
                let newsId = $(this).data('id');
                Swal.fire({
                    title: 'Emin misiniz?',
                    text: "Kapak resmini silmek istediğinize emin misiniz?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Evet, sil!',
                    cancelButtonText: 'Hayır'
                }).then((result) => {
                    if(result.isConfirmed){
                        $.ajax({
                            url: `/FT23BA23DG12/news/delete-cover/${newsId}`,
                            method: 'DELETE',
                            data: { _token: '{{ csrf_token() }}' },
                            success: function(response){
                                if(response.success){
                                    Swal.fire({
                                        title: 'Başarılı',
                                        text: response.message,
                                        icon: 'success'
                                    });
                                    $('#currentCoverContainer').remove();
                                } else {
                                    Swal.fire({
                                        title: 'Hata',
                                        text: response.message,
                                        icon: 'error'
                                    });
                                }
                            },
                            error: function(){
                                Swal.fire({
                                    title: 'Hata',
                                    text: 'Bir hata oluştu, lütfen tekrar deneyin.',
                                    icon: 'error'
                                });
                            }
                        });
                    }
                });
            });

            /* Sürükle Bırak + Yeni Ek Resim Yükleme */
            const dropzone       = $('#imagesDropzone');
            const fileInput      = $('#news_images');
            const previewCont    = $('#imagePreview');
            let newUploadedFiles = [];

            // Dropzone tıklanınca file input aç
            dropzone.on('click', function(e){
                e.preventDefault();
                e.stopPropagation();
                fileInput.trigger('click');
            });

            // Dosya seçildiğinde
            fileInput.on('change', function(e){
                const files = e.target.files;
                handleNewFiles(files);
            });

            // Sürükle-bırak olayları
            dropzone.on('dragover', function(e){
                e.preventDefault();
                e.stopPropagation();
                dropzone.addClass('bg-light');
            });
            dropzone.on('dragleave', function(e){
                e.preventDefault();
                e.stopPropagation();
                dropzone.removeClass('bg-light');
            });
            dropzone.on('drop', function(e){
                e.preventDefault();
                e.stopPropagation();
                dropzone.removeClass('bg-light');
                const files = e.originalEvent.dataTransfer.files;
                handleNewFiles(files);
            });

            function handleNewFiles(files){
                Array.from(files).forEach(file => {
                    if(file.size > 2 * 1024 * 1024){
                        alert('Dosya boyutu 2MB\'ı geçemez: ' + file.name);
                        return;
                    }
                    if(!['image/jpeg','image/png','image/jpg','image/gif'].includes(file.type)){
                        alert('Geçersiz dosya türü: ' + file.name);
                        return;
                    }
                    newUploadedFiles.push(file);
                    const reader = new FileReader();
                    reader.onload = function(e){
                        const imgHtml = `
                            <div class="me-3 mb-3 position-relative preview-image">
                                <img src="${e.target.result}" alt="Yeni Resim"
                                     style="width: 100px; height: 100px; object-fit: cover;">
                                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 delete-new-image"
                                        data-index="${newUploadedFiles.length - 1}">&times;</button>
                            </div>
                        `;
                        previewCont.append(imgHtml);
                    };
                    reader.readAsDataURL(file);
                });

                // file input'u güncellemek için DataTransfer
                const dataTransfer = new DataTransfer();
                newUploadedFiles.forEach(file => dataTransfer.items.add(file));
                fileInput[0].files = dataTransfer.files;
            }

            // Yeni ek resimleri önizlemeden silmek
            $(document).on('click', '.delete-new-image', function(){
                const index = $(this).data('index');
                newUploadedFiles.splice(index, 1);
                $(this).closest('.preview-image').remove();

                const dataTransfer = new DataTransfer();
                newUploadedFiles.forEach(file => dataTransfer.items.add(file));
                fileInput[0].files = dataTransfer.files;

                // Sil butonlarının index değerlerini güncelle
                $('.delete-new-image').each(function(i){
                    $(this).attr('data-index', i);
                });
            });

            /* Mevcut Ek Resimleri Silme İşlemi */
            $(document).on('click', '.delete-existing-image', function(){
                const imageId = $(this).data('id');
                Swal.fire({
                    title: 'Emin misiniz?',
                    text: "Bu resmi silmek istediğinize emin misiniz?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Evet, sil!',
                    cancelButtonText: 'Hayır'
                }).then((result) => {
                    if(result.isConfirmed){
                        $.ajax({
                            url: `/FT23BA23DG12/news/delete-image/${imageId}`,
                            method: 'DELETE',
                            data: { _token: '{{ csrf_token() }}' },
                            success: function(response){
                                if(response.success){
                                    Swal.fire({
                                        title: 'Başarılı',
                                        text: response.message,
                                        icon: 'success'
                                    });
                                    // Silinen resmi DOM'dan kaldır
                                    $(`[data-image-id="${imageId}"]`).remove();
                                } else {
                                    Swal.fire({
                                        title: 'Hata',
                                        text: response.message,
                                        icon: 'error'
                                    });
                                }
                            },
                            error: function(){
                                Swal.fire({
                                    title: 'Hata',
                                    text: 'Bir hata oluştu, lütfen tekrar deneyin.',
                                    icon: 'error'
                                });
                            }
                        });
                    }
                });
            });

            /* Form Gönderimi (AJAX) */
            $('#newsEditForm').submit(function(e){
                e.preventDefault();
                let formData = new FormData(this);
                // CKEditor içeriğini al
                formData.set('content', window.newsEditor.getData());
                $.ajax({
                    url: "{{ route('admin.news.update', $news->id) }}",
                    method: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response){
                        if(response.success){
                            Swal.fire({
                                title: 'Başarılı',
                                text: response.message,
                                icon: 'success'
                            }).then(() => {
                                window.location.href = "{{ route('admin.news.index') }}";
                            });
                        }
                    },
                    error: function(){
                        Swal.fire({
                            title: 'Hata',
                            text: 'Bir hata oluştu, lütfen tekrar deneyin.',
                            icon: 'error'
                        });
                    }
                });
            });
        });
    </script>
@endsection
