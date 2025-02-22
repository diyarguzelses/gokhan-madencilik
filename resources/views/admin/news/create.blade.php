@extends('admin.layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3>Yeni Haber Ekle</h3>
            </div>
            <br>
            <div class="card-body">
                <form id="newsCreateForm" enctype="multipart/form-data">
                    @csrf
                    <!-- Haber Başlığı -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Başlık</label>
                        <input type="text" name="title" id="title" class="form-control" required>
                    </div>
                    <!-- Haber İçeriği -->
                    <div class="mb-3">
                        <label for="content" class="form-label">İçerik</label>
                        <textarea name="content" id="content" class="form-control" rows="6"></textarea>
                    </div>

                    <!-- Kapak Fotoğrafı (News modelinde saklanacak) -->
                    <div class="mb-3">
                        <label for="cover_image" class="form-label">Kapak Fotoğrafı</label>
                        <input type="file" name="cover_image" id="cover_image" class="form-control" accept="image/*">
                        <img id="coverPreview" src="#" alt="Kapak Fotoğrafı Önizleme" style="display: none; margin-top: 10px; width: 150px; height: auto;">
                    </div>

                    <!-- Haber Görselleri (Ekstra) -->
                    <div class="mb-3">
                        <label class="form-label">Haber Görselleri Yükle</label>
                        <div id="imagesDropzone" class="border border-dashed text-center p-5" style="cursor: pointer;">
                            <p class="fw-bold">Sürükle ve Bırak veya Tıklayın</p>
                            <p class="text-muted">Birden fazla dosya yükleyebilirsiniz (JPEG, PNG, JPG - Maksimum 2MB).</p>
                        </div>
                        <!-- Controller "images[]" beklediği için input name güncellendi -->
                        <input type="file" id="news_images" name="images[]" class="d-none" multiple accept="image/*">
                        <div id="newsImagesPreview" class="d-flex flex-wrap mt-3"></div>
                    </div>

                    <button type="submit" class="btn btn-primary">Kaydet</button>
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

        // CKEditor'ü başlatıyoruz
        ClassicEditor
            .create(document.querySelector('#content'), {
                extraPlugins: [ MyCustomUploadAdapterPlugin ]
            })
            .then(editor => {
                window.newsEditor = editor;
                console.log('CKEditor custom adapter ile yüklendi.');
            })
            .catch(error => {
                console.error('CKEditor yüklenirken hata oluştu:', error);
            });

        $(document).ready(function(){
            /* Kapak Fotoğrafı Önizlemesi */
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

            /* Haber Görselleri (Ekstra) Yükleme İşlemleri */
            const imagesDropzone  = $('#imagesDropzone');
            const newsImagesInput = $('#news_images');
            const newsImagesPreview = $('#newsImagesPreview');
            let uploadedNewsImages = [];

            // Tıklama ile dosya seçimini tetikle
            imagesDropzone.on('click', function(e){
                e.preventDefault();
                e.stopPropagation();
                newsImagesInput.trigger('click');
            });

            // Dosya seçildiğinde
            newsImagesInput.on('change', function(e){
                const files = e.target.files;
                handleNewsImages(files);
            });

            // Sürükle ve Bırak olayları
            imagesDropzone.on('dragover', function(e){
                e.preventDefault();
                e.stopPropagation();
                imagesDropzone.addClass('bg-light');
            });

            imagesDropzone.on('dragleave', function(e){
                e.preventDefault();
                e.stopPropagation();
                imagesDropzone.removeClass('bg-light');
            });

            imagesDropzone.on('drop', function(e){
                e.preventDefault();
                e.stopPropagation();
                imagesDropzone.removeClass('bg-light');
                const files = e.originalEvent.dataTransfer.files;
                handleNewsImages(files);
            });

            function handleNewsImages(files){
                Array.from(files).forEach(file => {
                    // Boyut ve dosya tipi kontrolü
                    if(file.size > 2 * 1024 * 1024){
                        alert('Dosya boyutu 2MB\'ı geçemez: ' + file.name);
                        return;
                    }
                    if(!['image/jpeg','image/png','image/jpg','image/gif'].includes(file.type)){
                        alert('Geçersiz dosya türü: ' + file.name);
                        return;
                    }

                    uploadedNewsImages.push(file);
                    const reader = new FileReader();
                    reader.onload = function(e){
                        const imgHtml = `
                            <div class="me-3 mb-3 position-relative preview-image">
                                <img src="${e.target.result}" alt="Haber Fotoğrafı" style="width: 100px; height: 100px; object-fit: cover;">
                                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 delete-news-image" data-index="${uploadedNewsImages.length - 1}">&times;</button>
                            </div>
                        `;
                        newsImagesPreview.append(imgHtml);
                    };
                    reader.readAsDataURL(file);
                });
                // Input alanını güncellemek için DataTransfer kullanımı
                const dataTransfer = new DataTransfer();
                uploadedNewsImages.forEach(file => dataTransfer.items.add(file));
                newsImagesInput[0].files = dataTransfer.files;
            }

            // Haber fotoğraflarını önizleme alanından silmek
            $(document).on('click', '.delete-news-image', function(){
                const index = $(this).data('index');
                uploadedNewsImages.splice(index, 1);
                $(this).closest('.preview-image').remove();

                const dataTransfer = new DataTransfer();
                uploadedNewsImages.forEach(file => dataTransfer.items.add(file));
                newsImagesInput[0].files = dataTransfer.files;

                // Sil butonlarının index değerlerini güncelle
                $('.delete-news-image').each(function(i){
                    $(this).attr('data-index', i);
                });
            });

            /* Form Gönderimi (AJAX) */
            $('#newsCreateForm').submit(function(e){
                e.preventDefault();
                let formData = new FormData(this);
                // CKEditor içeriğini textarea'ya aktar
                formData.set('content', window.newsEditor.getData());
                $.ajax({
                    url: "{{ route('admin.news.store') }}",
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
