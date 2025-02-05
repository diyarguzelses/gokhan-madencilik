@extends('admin.layouts.app')

@section('content')
    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between">
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
                            <textarea class="form-control" name="content" id="content" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Görsel</label>
                            <input type="file" class="form-control" name="image" id="image">
                            <img id="previewImage" src="" class="mt-2 img-thumbnail" style="max-width: 100px; display: none;">
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
            let table = $('#pagesTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.pages.data') }}',
                language: {
                    url: "{{ asset('assets/datatables/turkish.json') }}"
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'title', name: 'title'},
                    {
                        data: 'content',
                        name: 'content',
                        render: function(data, type, row) {
                            return data.length > 200 ? data.substring(0, 200) + '...' : data;
                        }
                    },
                    {
                        data: 'image',
                        name: 'image',
                        orderable: false,
                        searchable: false,
                        render: function (data) {
                            return data ? `<img src="/${data}" class="img-thumbnail" width="50">` : 'Yok';
                        }
                    },
                    {data: 'actions', name: 'actions', orderable: false, searchable: false}
                ]
            });

            $('#addPageBtn').click(function () {
                $('#pageForm')[0].reset();
                $('#page_id').val('');
                $('#previewImage').hide();
                $('#pageModal').modal('show');
            });

            $(document).on('click', '.edit-page', function () {
                let pageId = $(this).data('id');

                $.get(`/admin/pages/${pageId}/edit`, function (data) {
                    $('#page_id').val(data.id);
                    $('#title').val(data.title);
                    $('#content').val(data.content);
                    if (data.image) {
                        $('#previewImage').attr('src', '/' + data.image).show();
                    } else {
                        $('#previewImage').hide();
                    }
                    $('#pageModal').modal('show');
                });
            });

            $('#pageForm').submit(function (e) {
                e.preventDefault();
                let formData = new FormData(this);
                let pageId = $('#page_id').val();
                let url = pageId ? `/admin/pages/${pageId}` : '/admin/pages';
                let method = pageId ? 'POST' : 'POST';

                if (pageId) {
                    formData.append('_method', 'PUT');
                }

                $.ajax({
                    url: url,
                    method: method,
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
                            url: `/admin/pages/${pageId}`,
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
