@extends('admin.layouts.app')

@section('content')
    <div class="alert alert-primary mt-2">
        <h5>Haberler Yönetimi Hakkında</h5>
        <p>Bu panel, web sitesinde yayınlanacak haberlerin eklenmesi, düzenlenmesi ve silinmesi için kullanılır. Haberler başlık, içerik ve görsel ile birlikte kaydedilir.</p>

        <h6>Girdi Alanları:</h6>
        <ul>
            <li><strong>Başlık:</strong> Haberin başlığını belirten zorunlu bir alan.</li>
            <li><strong>İçerik:</strong> Haberin detaylı içeriğini içeren zorunlu bir alan.</li>
            <li><strong>Resim:</strong> Opsiyonel bir alan olup, habere bir görsel eklemeyi sağlar.</li>
        </ul>
    </div>

    <div class="alert alert-primary mt-2">
        <h5>Ek bilgi</h5>
        <p>
            Yeşil arka plan, anasayfada gösterilen haberi ifade eder. Aynı zamanda haberleri sürükleyerek
            sıralayabilir, düzenleyebilir veya silebilirsiniz.
        </p>

    </div>

    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between" style="background: linear-gradient(135deg, #1e3c72, #2a5298); color: white; border-radius: 10px 10px 0 0;">
            <span class="fw-bold fs-5">Haberler</span>
            <button class="btn btn-light text-primary fw-bold" id="addNewsBtn">
                <i class="bi bi-plus-circle"></i> Yeni Haber Ekle
            </button>
        </div>
        <div class="card-body mt-5">
            <table id="newsTable" class="table table-bordered">
                <thead>
                <tr>
                    <th>Sıra</th>
                    <th>ID</th>
                    <th>Başlık</th>
                    <th>İçerik</th>
                    <th>Resim</th>
                    <th>İşlemler</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
            <small class="text-muted">Satırları tutup sürükleyerek haberlerin sırasını değiştirebilirsiniz.</small>
        </div>
    </div>

    <!-- Haber Ekle & Düzenle Modal -->
    <div class="modal fade" id="newsModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Haber Bilgileri</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="newsForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="news_id">
                        <input type="hidden" name="_method" id="_method">
                        <div class="mb-3">
                            <label>Başlık</label>
                            <input type="text" class="form-control" name="title" id="title" required>
                        </div>
                        <div class="mb-3">
                            <label>İçerik</label>
                            <textarea class="form-control" name="content" id="content"></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Resim</label>
                            <input type="file" class="form-control" name="image" id="image">
                            <div id="newsImageContainer" style="position: relative; display: inline-block;">
                                <img id="previewImage" class="mt-2" width="100" height="100" style="display: none;">
                                <span id="deleteNewsImageIcon" style="position: absolute; top:0; right: 0; background: red; color: white; padding: 6px 10px; border-radius: 5px; cursor: pointer; display: none;">&times;</span>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Kaydet</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- CKEditor 5 Classic Editor CDN -->
    <script src="https://cdn.ckeditor.com/ckeditor5/35.0.1/classic/ckeditor.js"></script>
    <!-- jQuery UI Sortable CDN -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script>
        $(document).ready(function () {
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



            let table = $('#newsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route("admin.news.data") }}',
                language: {
                    url: "{{ asset('assets/datatables/turkish.json') }}"
                },
                columns: [
                    { data: 'order', name: 'order' },
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
                    { data: 'image', name: 'image', orderable: false, searchable: false },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false }
                ],
                createdRow: function (row, data, dataIndex) {
                    if (data.frontpage) {
                        $(row).find('td').not(':last-child').css('background-color', '#90ee90');
                    }
                }
            });

            // Her çizimde satırlara data-id attribute ekleyelim
            table.on('draw.dt', function() {
                $('#newsTable tbody tr').each(function() {
                    var data = table.row(this).data();
                    if (data && data.id) {
                        $(this).attr('data-id', data.id);
                    }
                });
            });

            // jQuery UI Sortable: Satırları sürükleyerek sıralama
            $("#newsTable tbody").sortable({
                helper: function(e, tr) {
                    tr.children().each(function() {
                        $(this).width($(this).width());
                    });
                    return tr;
                },
                update: function(event, ui) {
                    let orders = [];
                    $("#newsTable tbody tr").each(function(index) {
                        let newsId = $(this).attr('data-id');
                        if (newsId) {
                            orders.push({ id: newsId, order: index + 1 });
                        }
                    });
                    if (orders.length === 0) {
                        Swal.fire({
                            title: 'Uyarı',
                            text: 'Sıralanacak haber bulunamadı.',
                            icon: 'warning'
                        });
                        return;
                    }
                    // AJAX ile yeni sıralamayı gönder
                    $.ajax({
                        url: "{{ route('admin.news.news-updateOrder') }}",
                        method: "POST",
                        data: JSON.stringify({
                            _token: "{{ csrf_token() }}",
                            orders: orders
                        }),
                        contentType: "application/json; charset=utf-8",
                        dataType: "json",
                        success: function(response) {
                            Swal.fire({
                                title: 'Başarılı',
                                text: response.message,
                                icon: 'success'
                            }).then(function() {
                                table.ajax.reload();
                            });
                        },
                        error: function() {
                            Swal.fire({
                                title: 'Hata',
                                text: 'Haber sırası güncellenirken bir hata oluştu.',
                                icon: 'error'
                            });
                        }
                    });
                }
            });

            // Yeni Haber Ekle Butonu
            $('#addNewsBtn').click(function () {
                $('#newsForm')[0].reset();
                $('#news_id').val('');
                $('#_method').val('POST');
                $('#previewImage').hide();
                $('#deleteNewsImageIcon').hide();
                if (window.courseEditor) {
                    window.courseEditor.setData('');
                }
                $('#newsModal').modal('show');
            });

            // AJAX ile Haber Düzenleme: "Düzenle" butonuna tıklandığında içerik çekme
            $(document).on('click', '.edit-news', function () {
                let newsId = $(this).data('id');
                $.ajax({
                    url: `/FT23BA23DG12/news/get-content/${newsId}`,
                    type: 'GET',
                    success: function(response) {
                        // Modal alanlarını dolduralım
                        $('#news_id').val(response.id);
                        $('#title').val(response.title);
                        window.courseEditor.setData(response.content);
                        $('#_method').val('PUT');

                        if (response.image) {
                            $('#previewImage').attr('src', '/uploads/news/' + response.image).show();
                            $('#deleteNewsImageIcon').show().data('id', response.id);
                        } else {
                            $('#previewImage').hide();
                            $('#deleteNewsImageIcon').hide();
                        }
                        $('#newsModal').modal('show');
                    },
                    error: function() {
                        Swal.fire('Hata!', 'İçerik çekilirken bir hata oluştu, lütfen tekrar deneyin.', 'error');
                    }
                });
            });

            // Form Gönderimi (Yeni Kayıt veya Güncelleme)
            $('#newsForm').submit(function (e) {
                e.preventDefault();
                let formData = new FormData(this);
                let newsId = $('#news_id').val();
                let url = newsId ? `/FT23BA23DG12/news/${newsId}` : '/FT23BA23DG12/news';

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        table.ajax.reload();
                        $('#newsModal').modal('hide');
                        Swal.fire('Başarılı', response.message, 'success');
                    },
                    error: function () {
                        Swal.fire('Hata', 'Bir hata oluştu, lütfen tekrar deneyin.', 'error');
                    }
                });
            });

            // Haber Silme Butonu
            $(document).on('click', '.delete-news', function () {
                let newsId = $(this).data('id');
                Swal.fire({
                    title: 'Emin misiniz?',
                    text: "Bu haberi silmek istediğinize emin misiniz?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Evet, sil!',
                    cancelButtonText: 'Hayır'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/FT23BA23DG12/news/${newsId}`,
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

            // Resim Seçildiğinde Önizleme
            $('#image').change(function (e) {
                let reader = new FileReader();
                reader.onload = function (e) {
                    $('#previewImage').attr('src', e.target.result).show();
                    $('#deleteNewsImageIcon').hide();
                };
                reader.readAsDataURL(this.files[0]);
            });

            $(document).on('click', '#deleteNewsImageIcon', function () {
                let newsId = $(this).data('id');
                $.ajax({
                    url: `/FT23BA23DG12/news/delete-image/${newsId}`,
                    method: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function (response) {
                        if (response.success) {
                            $('#previewImage').attr('src', '').hide();
                            $('#image').val('');
                            $('#deleteNewsImageIcon').hide();
                        } else {
                            Swal.fire('Hata!', response.message, 'error');
                        }
                    },
                    error: function () {
                        Swal.fire('Hata!', 'Bir hata oluştu, lütfen tekrar deneyin.', 'error');
                    }
                });
            });
        });

        // Toggle Frontpage İşlemi
        $(document).on('click', '.toggle-frontpage', function () {
            let newsId = $(this).data('id');
            $.ajax({
                url: `/FT23BA23DG12/news/toggle-frontpage/${newsId}`,
                method: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function (response) {
                    if (response.success) {
                        Swal.fire('Başarılı', response.message, 'success');
                        $('#newsTable').DataTable().ajax.reload(null, false);
                    } else {
                        Swal.fire('Hata!', response.message, 'error');
                    }
                },
                error: function () {
                    Swal.fire('Hata!', 'Bir hata oluştu, lütfen tekrar deneyin.', 'error');
                }
            });
        });
    </script>
@endsection
