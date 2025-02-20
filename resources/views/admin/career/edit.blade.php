@extends('admin.layouts.app')

@section('content')
    <div class="alert alert-primary mt-2">
        <h5>Kariyer Sayfası Yönetimi Hakkında</h5>
        <p>Bu panel, web sitesinin kariyer sayfasındaki içeriğin güncellenmesi için kullanılır. Adminler, kariyer fırsatları hakkında bilgilendirme metni ekleyebilir ve sayfa için bir görsel yükleyebilir.</p>

        <h6>Girdi Alanları:</h6>
        <ul>
            <li><strong>İçerik:</strong> Kariyer sayfasında görüntülenecek açıklama metni.</li>
            <li><strong>Görsel:</strong> Opsiyonel bir alan olup, sayfaya bir görsel eklemeyi sağlar.</li>
        </ul>
    </div>

    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between" style="background: linear-gradient(135deg, #1e3c72, #2a5298); border-radius: 10px 10px 0 0;">
            <span class="fw-bold fs-5">Kariyer Sayfası</span>
        </div>
        <div class="card-body mt-3">
            <form id="careerForm" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label>İçerik</label>
                    <textarea class="form-control" name="content" id="content" >{{ $career->content ?? '' }}</textarea>
                </div>
                <div class="mb-3">
                    <label>Görsel</label>
                    <input type="file" class="form-control" name="image" id="image">
                    <div id="imageContainer" style="position: relative; display: inline-block;">
                        @if (!empty($career->image))
                            <img src="{{ asset('uploads/career/'.$career->image) }}" id="previewImage" class="mt-2" width="100" height="100">
                            <span id="deleteCareerImageIcon" style="position: absolute; top: 0; right: 0; background: red; color: white; padding: 4px 8px; border-radius: 5px; cursor: pointer;">&times;</span>
                        @else
                            <img id="previewImage" class="mt-2" width="100" height="100" style="display:none;">
                        @endif
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100">Kaydet</button>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <!-- CKEditor 5 Classic Editor CDN -->
    <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
    <script>
        $(document).ready(function () {
            // CKEditor'ü başlatıyoruz.
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
                .create(document.querySelector('#content'), {
                    extraPlugins: [ MyCustomUploadAdapterPlugin ],

                })
                .then(editor => {
                    window.courseEditor = editor;
                    console.log('Editor custom adapter ile yüklendi.');
                })
                .catch(error => {
                    console.error('CKEditor yüklenirken hata oluştu:', error);
                });

            // Form gönderimi
            $('#careerForm').submit(function (e) {
                e.preventDefault();
                let formData = new FormData(this);
                // CKEditor içeriğini textarea'ya aktarıyoruz
                formData.set('content', window.careerEditor.getData());

                $.ajax({
                    url: '{{ route("admin.career.update") }}',
                    method: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        Swal.fire('Başarılı', response.message, 'success').then(() => {
                            location.reload();
                        });

                    },
                    error: function () {
                        Swal.fire('Hata', 'Bir hata oluştu, lütfen tekrar deneyin.', 'error');
                    }
                });
            });

            // Görsel seçildiğinde önizleme
            $('#image').change(function (e) {
                let reader = new FileReader();
                reader.onload = function (e) {
                    $('#previewImage').attr('src', e.target.result).show();
                };
                reader.readAsDataURL(this.files[0]);
            });

            // Görsel silme (çarpı simgesine tıklandığında)
            $(document).on('click', '#deleteCareerImageIcon', function () {
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
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route("admin.career.career-deleteImage") }}',
                            method: 'DELETE',
                            data: { _token: '{{ csrf_token() }}' },
                            success: function (response) {
                                if (response.success) {
                                    $('#previewImage').attr('src', '').hide();
                                    $('#deleteCareerImageIcon').hide();
                                    $('#image').val('');
                                    Swal.fire('Silindi!', response.message, 'success');
                                } else {
                                    Swal.fire('Hata!', response.message, 'error');
                                }
                            },
                            error: function () {
                                Swal.fire('Hata!', 'Bir hata oluştu, lütfen tekrar deneyin.', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
