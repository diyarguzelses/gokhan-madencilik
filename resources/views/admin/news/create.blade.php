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
                    <div class="mb-3">
                        <label for="title" class="form-label">Başlık</label>
                        <input type="text" name="title" id="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">İçerik</label>
                        <textarea name="content" id="content" class="form-control" rows="6"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Resim</label>
                        <input type="file" name="image" id="image" class="form-control" accept="image/*">
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
        // Custom Upload Adapter Plugin for CKEditor
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

        // CKEditor'ü custom adapter ile başlatıyoruz
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
    </script>
    <script>
        $(document).ready(function(){
            $('#newsCreateForm').submit(function(e){
                e.preventDefault();
                let formData = new FormData(this);
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
