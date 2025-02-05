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

    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between" style="background: linear-gradient(135deg, #1e3c72, #2a5298); color: white; border-radius: 10px 10px 0 0;">
            <span class="fw-bold fs-5">Haberler</span>
            <button class="btn btn-light text-primary fw-bold" id="addNewsBtn">
                <i class="bi bi-plus-circle"></i> Yeni Haber Ekle
            </button>
        </div>
        <div class="card-body">
            <table id="newsTable" class="table table-bordered">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Başlık</th>
                    <th>İçerik</th>
                    <th>Resim</th>
                    <th>İşlemler</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
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
                            <textarea class="form-control" name="content" id="content" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Resim</label>
                            <input type="file" class="form-control" name="image" id="image">
                            <img id="previewImage" class="mt-2" width="100" height="100" style="display:none;">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Kaydet</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            let table = $('#newsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.news.data') }}',
                language: {
                    url: "{{ asset('assets/datatables/turkish.json') }}" // Türkçe çeviri dosyasını yükle
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'title', name: 'title'},
                    {data: 'content', name: 'content'},
                    {data: 'image', name: 'image', orderable: false, searchable: false},
                    {data: 'actions', name: 'actions', orderable: false, searchable: false}
                ]
            });

            // Yeni Haber Ekle Butonu
            $('#addNewsBtn').click(function () {
                $('#newsForm')[0].reset();
                $('#news_id').val('');
                $('#_method').val('POST');
                $('#newsModal').modal('show');
            });

            // Haber Düzenleme Butonu
            $(document).on('click', '.edit-news', function () {
                $('#news_id').val($(this).data('id'));
                $('#title').val($(this).data('title'));
                $('#content').val($(this).data('content'));
                $('#_method').val('PUT');

                let image = $(this).data('image');
                if (image) {
                    $('#previewImage').attr('src', '/uploads/news/' + image).show();
                } else {
                    $('#previewImage').hide();
                }

                $('#newsModal').modal('show');
            });

            // Form Gönderme (Yeni Kayıt veya Güncelleme)
            $('#newsForm').submit(function (e) {
                e.preventDefault();
                let formData = new FormData(this);
                let newsId = $('#news_id').val();
                let url = newsId ? `/admin/news/${newsId}` : '/admin/news';
                let method = newsId ? 'POST' : 'POST';

                $.ajax({
                    url: url,
                    method: method,
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

            // Silme Butonu
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
                            url: `/admin/news/${newsId}`,
                            method: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
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
    </script>
@endsection
