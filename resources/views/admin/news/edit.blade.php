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
                    <div class="mb-3">
                        <label for="title" class="form-label">Başlık</label>
                        <input type="text" name="title" id="title" class="form-control" value="{{ $news->title }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">İçerik</label>
                        <textarea name="content" id="content" class="form-control" rows="6" required>{{ $news->content }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Resim</label>
                        @if($news->image)
                            <div class="mb-2" id="currentImageContainer">
                                <img src="{{ asset('uploads/news/' . $news->image) }}" alt="Haber Resmi" width="100" id="currentImage">
                                <button type="button" class="btn btn-danger btn-sm" id="deleteImageBtn" data-id="{{ $news->id }}">Resmi Sil</button>
                            </div>
                        @endif
                        <input type="file" name="image" id="image" class="form-control" accept="image/*">
                        <!-- Dosya seçildiğinde önizleme için eklenen img -->
                        <img id="previewImage" src="#" alt="Önizleme" style="display: none; margin-top: 10px; max-width: 100%; max-height: 200px;">
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
            abort() {}
        }

        // CKEditor'ü custom adapter ile başlatıyoruz (textarea id'si "text")
        ClassicEditor
            .create(document.querySelector('#content'), {
                extraPlugins: [ MyCustomUploadAdapterPlugin ]
            })
            .then(editor => {
                window.sectorEditor = editor;
                console.log('Editor custom adapter ile yüklendi.');
            })
            .catch(error => {
                console.error('CKEditor yüklenirken hata oluştu:', error);
            });
    </script>
    <script>
        $(document).ready(function(){
            // Form gönderimi (AJAX)
            $('#newsEditForm').submit(function(e){
                e.preventDefault();
                let formData = new FormData(this);
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

            // Mevcut fotoğrafı silme işlemi
            $('#deleteImageBtn').click(function(){
                let newsId = $(this).data('id');
                Swal.fire({
                    title: 'Emin misiniz?',
                    text: "Mevcut resmi silmek istediğinize emin misiniz?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Evet, sil!',
                    cancelButtonText: 'Hayır'
                }).then((result) => {
                    if(result.isConfirmed){
                        $.ajax({
                            url: `/FT23BA23DG12/news/delete-image/${newsId}`,
                            method: 'DELETE',
                            data: { _token: '{{ csrf_token() }}' },
                            success: function(response){
                                if(response.success){
                                    Swal.fire({
                                        title: 'Başarılı',
                                        text: response.message,
                                        icon: 'success'
                                    });
                                    $('#currentImageContainer').remove();
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

            // Dosya seçildiğinde önizleme işlemi
            $('#image').change(function () {
                let file = this.files[0];
                if(file){
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        $('#previewImage').attr('src', e.target.result).show();
                    };
                    reader.readAsDataURL(file);
                } else {
                    $('#previewImage').hide();
                }
            });
        });
    </script>
@endsection
