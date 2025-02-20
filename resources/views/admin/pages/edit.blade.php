@extends('admin.layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3>Sayfa Düzenle</h3>
            </div>
            <div class="card-body">
                <form id="pageEditForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="title" class="form-label">Sayfa Başlığı</label>
                        <input type="text" name="title" id="title" class="form-control" value="{{ $page->title }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">İçerik</label>
                        <textarea name="content" id="content" class="form-control" rows="6">{{ $page->content }}</textarea>
                    </div>
                    <!-- Çoklu Görsel Yükleme Alanı -->
                    <div class="mb-3">
                        <label class="form-label">Sayfa Görselleri</label>
                        <div id="imageDropzone" class="border border-dashed text-center p-3" style="cursor: pointer;">
                            <p class="fw-bold">Sürükle ve Bırak veya Tıklayın</p>
                            <p class="text-muted">Birden fazla dosya yükleyebilirsiniz (JPEG, PNG, JPG, GIF - Maksimum 2MB).</p>
                        </div>
                        <input type="file" id="images" name="images[]" class="d-none" multiple>
                        <div id="imagePreview" class="d-flex flex-wrap mt-2">
                            @if($page->images)
                                @foreach($page->images as $img)
                                    <div class="me-2 mb-2 position-relative existing-image">
                                        <img src="{{ asset($img->image) }}" class="img-thumbnail" width="80">
                                        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 delete-existing-image" data-id="{{ $img->id }}">&times;</button>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Güncelle</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
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
            $('#pageEditForm').submit(function(e){
                e.preventDefault();
                let formData = new FormData(this);
                let pageId = "{{ $page->id }}";
                formData.append('_method', 'PUT');
                $.ajax({
                    url: `/FT23BA23DG12/pages/${pageId}`,
                    method: 'POST',
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

            // Mevcut görsellerin silinmesi
            $(document).on('click', '.delete-existing-image', function(){
                let imageId = $(this).data('id');
                let $btn = $(this);
                Swal.fire({
                    title: 'Emin misiniz?',
                    text: "Bu görseli silmek istediğinize emin misiniz?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Evet, sil!',
                    cancelButtonText: 'İptal'
                }).then((result) => {
                    if(result.isConfirmed){
                        $.ajax({
                            url: `/FT23BA23DG12/pages/page-images/${imageId}`,
                            method: 'DELETE',
                            data: { _token: '{{ csrf_token() }}' },
                            success: function(response){
                                Swal.fire('Başarılı', response.message, 'success').then(() => {
                                    window.location.reload();
                                });
                                $btn.closest('.existing-image').remove();
                            },
                            error: function(){
                                Swal.fire('Hata', 'Bir hata oluştu, lütfen tekrar deneyin.', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
