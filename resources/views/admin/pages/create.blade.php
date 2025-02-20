@extends('admin.layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3>Yeni Sayfa Ekle</h3>
            </div>
            <div class="card-body">
                <form id="pageCreateForm" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="title" class="form-label">Sayfa Başlığı</label>
                        <input type="text" name="title" id="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">İçerik</label>
                        <textarea name="content" id="content" class="form-control" rows="6"></textarea>
                    </div>
                    <!-- Çoklu Görsel Yükleme Alanı -->
                    <div class="mb-3">
                        <label class="form-label">Sayfa Görselleri</label>
                        <div id="imageDropzone" class="border border-dashed text-center p-3" style="cursor: pointer;">
                            <p class="fw-bold">Sürükle ve Bırak veya Tıklayın</p>
                            <p class="text-muted">Birden fazla dosya yükleyebilirsiniz (JPEG, PNG, JPG, GIF - Maksimum 2MB).</p>
                        </div>
                        <input type="file" id="images" name="images[]" class="d-none" multiple>
                        <div id="imagePreview" class="d-flex flex-wrap mt-2"></div>
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
        // Custom Upload Adapter Plugin
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
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
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

        ClassicEditor.create(document.querySelector('#content'), {
            extraPlugins: [ MyCustomUploadAdapterPlugin ]
        }).then(editor => {
            window.pageEditor = editor;
            console.log('Page Editor custom adapter ile yüklendi.');
        }).catch(error => {
            console.error('CKEditor yüklenirken hata oluştu:', error);
        });
    </script>
    <script>
        $(document).ready(function(){
            const dropzone = $('#imageDropzone');
            const fileInput = $('#images');
            const previewContainer = $('#imagePreview');
            let uploadedFiles = [];

            dropzone.on('click', function(e){
                e.preventDefault();
                fileInput.trigger('click');
            });
            fileInput.on('change', function(e){
                const files = e.target.files;
                handleFiles(files);
            });
            dropzone.on('dragover', function(e){
                e.preventDefault();
                dropzone.addClass('bg-light');
            });
            dropzone.on('dragleave', function(e){
                e.preventDefault();
                dropzone.removeClass('bg-light');
            });
            dropzone.on('drop', function(e){
                e.preventDefault();
                dropzone.removeClass('bg-light');
                const files = e.originalEvent.dataTransfer.files;
                handleFiles(files);
            });
            function handleFiles(files) {
                Array.from(files).forEach(file => {
                    if(file.size > 2 * 1024 * 1024) {
                        alert('Dosya boyutu 2MB\'ı geçemez: ' + file.name);
                        return;
                    }
                    if(!['image/jpeg','image/png','image/jpg','image/gif'].includes(file.type)) {
                        alert('Geçersiz dosya türü: ' + file.name);
                        return;
                    }
                    uploadedFiles.push(file);
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        const imgHtml = `
            <div class="me-3 mb-3 position-relative preview-image">
              <img src="${event.target.result}" alt="Görsel" style="width: 100px; height: 100px; object-fit: cover;">
              <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 delete-temp-image" data-index="${uploadedFiles.length - 1}">&times;</button>
            </div>`;
                        previewContainer.append(imgHtml);
                    };
                    reader.readAsDataURL(file);
                });
                const dataTransfer = new DataTransfer();
                uploadedFiles.forEach(file => dataTransfer.items.add(file));
                fileInput[0].files = dataTransfer.files;
            }
            $(document).on('click', '.delete-temp-image', function(){
                const index = $(this).data('index');
                uploadedFiles.splice(index, 1);
                $(this).closest('.preview-image').remove();
                const dataTransfer = new DataTransfer();
                uploadedFiles.forEach(file => dataTransfer.items.add(file));
                fileInput[0].files = dataTransfer.files;
                $('.delete-temp-image').each(function(i){
                    $(this).attr('data-index', i);
                });
            });

            // Form gönderimi AJAX ile
            $('#pageCreateForm').submit(function(e){
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    url: "{{ route('admin.pages.store') }}",
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response){
                        Swal.fire('Başarılı', response.message, 'success').then(() => {
                            window.location.href = "{{ route('admin.pages.index') }}";
                        });
                    },
                    error: function(){
                        Swal.fire('Hata', 'Bir hata oluştu, lütfen tekrar deneyin.', 'error');
                    }
                });
            });
        });
    </script>
@endsection
