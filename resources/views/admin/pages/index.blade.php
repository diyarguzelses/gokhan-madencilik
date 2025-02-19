@extends('admin.layouts.app')
<style>
    .swal2-container {
        z-index: 10000 !important;
    }
</style>
@section('content')
    <!-- Sayfa Yönetimi İçeriği -->
    <div class="alert alert-primary mt-2">
        <h5>Sayfa Yönetimi Hakkında</h5>
        <p>Bu panel, adminlerin web sitesine yeni sayfalar eklemesini, mevcut sayfaları düzenlemesini ve silmesini sağlar.</p>

        <h6>Girdi Alanları:</h6>
        <ul>
            <li><strong>Sayfa Başlığı:</strong> Sayfanın başlığını belirten zorunlu bir alan.</li>
            <li><strong>İçerik:</strong> Sayfanın ana metin içeriğini içerir, zorunludur.</li>
            <li><strong>Görsel:</strong> Opsiyonel bir alan olup, sayfaya bir görsel yüklemeyi sağlar.</li>
        </ul>
    </div>

    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between"  style="background: linear-gradient(135deg, #1e3c72, #2a5298); color: white; border-radius: 10px 10px 0 0;">
            <span class="fw-bold fs-5">Sayfa Yönetimi</span>
            <button class="btn btn-light text-primary fw-bold" id="addPageBtn">
                <i class="bi bi-plus-circle"></i> Yeni Sayfa Ekle
            </button>
        </div>
        <div class="card-body">
            <table id="pagesTable" class="table table-bordered">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Başlık</th>
                    <th>İçerik</th>
                    <th>Görsel</th>
                    <th>İşlemler</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <!-- Sayfa Ekle & Düzenle Modal -->
    <div class="modal fade" id="pageModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Sayfa Bilgileri</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="pageForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="page_id">
                        <div class="mb-3">
                            <label>Sayfa Başlığı</label>
                            <input type="text" class="form-control" name="title" id="title" required>
                        </div>
                        <div class="mb-3">
                            <label>İçerik</label>
                            <textarea class="form-control" name="content" id="content" ></textarea>
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
                        <button type="submit" class="btn btn-primary w-100">Kaydet</button>
                    </form>
                </div>
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
                                resolve({ default: result.url });
                            })
                            .catch(error => {
                                reject('Dosya yüklenirken hata oluştu: ' + error);
                            });
                    }));
            }
            abort() { }
        }

        // CKEditor Başlatma: window.pageEditor olarak ayarlıyoruz
        ClassicEditor
            .create(document.querySelector('#content'), {
                extraPlugins: [ MyCustomUploadAdapterPlugin ]
            })
            .then(editor => {
                window.pageEditor = editor; // Dikkat: pageEditor olarak kaydediyoruz.
                console.log('Editor custom adapter ile yüklendi.');
            })
            .catch(error => {
                console.error('CKEditor yüklenirken hata oluştu:', error);
            });

        $(document).ready(function () {
            let table = $('#pagesTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.pages.data') }}',
                language: {
                    url: "{{ asset('assets/datatables/turkish.json') }}"
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'title', name: 'title' },
                    {
                        data: 'content',
                        name: 'content',
                        render: function(data) {
                            let div = document.createElement("div");
                            div.innerHTML = data;
                            let plainText = div.textContent || div.innerText || "";
                            return plainText.length > 50 ? plainText.substring(0, 50) + '...' : plainText;
                        }
                    },
                    {
                        data: 'images',
                        name: 'images',
                        orderable: false,
                        searchable: false,
                        render: function(data) {
                            if (data && data.length > 0) {
                                let html = '';
                                data.forEach(function(image) {
                                    html += `<img src="/${image.image}" class="img-thumbnail me-1" width="50">`;
                                });
                                return html;
                            } else {
                                return 'Yok';
                            }
                        }
                    },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false }
                ]
            });

            // Yeni Sayfa Ekle Butonu
            $('#addPageBtn').click(function () {
                $('#pageForm')[0].reset();
                $('#page_id').val('');
                $('#imagePreview').html('');
                if (window.pageEditor) {
                    window.pageEditor.setData('');
                }
                $('#pageModal').modal('show');
            });

            // Sayfa Düzenleme İşlemi
            $(document).on('click', '.edit-page', function () {
                let pageId = $(this).data('id');
                $.get(`/FT23BA23DG12/pages/${pageId}/edit`, function (data) {
                    $('#page_id').val(data.id);
                    $('#title').val(data.title);
                    window.pageEditor.setData(data.content);
                    if(data.images && data.images.length > 0){
                        let previewHtml = '';
                        data.images.forEach(function(img) {
                            previewHtml += `
                            <div class="me-2 mb-2 position-relative existing-image">
                                <img src="/${img.image}" class="img-thumbnail" width="80">
                                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 delete-existing-image" data-id="${img.id}">&times;</button>
                            </div>`;
                        });
                        $('#imagePreview').html(previewHtml);
                    } else {
                        $('#imagePreview').html('');
                    }
                    $('#pageModal').modal('show');
                });
            });

            // Form Gönderimi
            $('#pageForm').submit(function (e) {
                e.preventDefault();
                let formData = new FormData(this);
                let pageId = $('#page_id').val();
                let url = pageId ? `/FT23BA23DG12/pages/${pageId}` : '/FT23BA23DG12/pages';

                if (pageId) {
                    formData.append('_method', 'PUT');
                }

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        $('#pageModal').modal('hide');
                        table.ajax.reload();
                        Swal.fire('Başarılı', response.message, 'success');
                    },
                    error: function () {
                        Swal.fire('Hata', 'Bir hata oluştu, lütfen tekrar deneyin.', 'error');
                    }
                });
            });

            // Sürükle ve Bırak ile Çoklu Dosya Yükleme
            const dropzone = $('#imageDropzone');
            const fileInput = $('#images');
            const previewContainer = $('#imagePreview');
            let uploadedFiles = [];

            dropzone.on('click', function (e) {
                e.preventDefault();
                fileInput.trigger('click');
            });

            fileInput.on('change', function (e) {
                const files = e.target.files;
                handleFiles(files);
            });

            dropzone.on('dragover', function (e) {
                e.preventDefault();
                dropzone.addClass('bg-light');
            });

            dropzone.on('dragleave', function (e) {
                e.preventDefault();
                dropzone.removeClass('bg-light');
            });

            dropzone.on('drop', function (e) {
                e.preventDefault();
                dropzone.removeClass('bg-light');
                const files = e.originalEvent.dataTransfer.files;
                handleFiles(files);
            });

            function handleFiles(files) {
                Array.from(files).forEach(file => {
                    if (file.size > 2 * 1024 * 1024) {
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
                const dataTransfer = new DataTransfer();
                uploadedFiles.forEach(file => dataTransfer.items.add(file));
                fileInput[0].files = dataTransfer.files;
            }

            $(document).on('click', '.delete-temp-image', function () {
                const index = $(this).data('index');
                uploadedFiles.splice(index, 1);
                $(this).closest('.preview-image').remove();
                const dataTransfer = new DataTransfer();
                uploadedFiles.forEach(file => dataTransfer.items.add(file));
                fileInput[0].files = dataTransfer.files;
                $('.delete-temp-image').each(function (i) {
                    $(this).attr('data-index', i);
                });
            });

            // Sayfa silme işlemi
            $(document).on('click', '.delete-page', function () {
                let pageId = $(this).data('id');
                Swal.fire({
                    title: 'Emin misiniz?',
                    text: "Bu sayfayı silmek istediğinize emin misiniz?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Evet, sil!',
                    cancelButtonText: 'Hayır'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/FT23BA23DG12/pages/${pageId}`,
                            method: 'DELETE',
                            data: { _token: '{{ csrf_token() }}' },
                            success: function (response) {
                                table.ajax.reload();
                                Swal.fire('Silindi!', response.message, 'success');
                            },
                            error: function () {
                                Swal.fire('Hata!', 'Bir hata oluştu, lütfen tekrar deneyin.', 'error');
                            }
                        });
                    }
                });
            });
        });

        $(document).on('click', '.delete-existing-image', function () {
            let imageId = $(this).data('id');
            let $btn = $(this);
            Swal.fire({
                zIndex: 20002133123,
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
                        url: `/FT23BA23DG12/pages/page-images/${imageId}`,
                        method: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function (response) {
                            Swal.fire({
                                zIndex: 20002133123,
                                title: 'Başarılı',
                                text: response.message,
                                icon: 'success'
                            }).then(() => {
                                window.location.reload();
                            });
                            $btn.closest('.existing-image').remove();
                        },
                        error: function () {
                            Swal.fire({
                                zIndex: 2000,
                                title: 'Hata',
                                text: 'Bir hata oluştu, lütfen tekrar deneyin.',
                                icon: 'error'
                            });
                        }
                    });
                }
            });
        });
    </script>
@endsection
