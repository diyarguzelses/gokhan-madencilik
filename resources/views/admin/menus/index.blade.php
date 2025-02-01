@extends('admin.layouts.app')

@section('content')
    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between">
            <span class="fw-bold fs-5">Menü Yönetimi</span>
            <button class="btn btn-light text-primary fw-bold" id="addMenuBtn">
                <i class="bi bi-plus-circle"></i> Yeni Menü Ekle
            </button>
        </div>
        <div class="card-body">
            <table id="menusTable" class="table table-bordered">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Üst Menü</th>
                    <th>Adı</th>
                    <th>Bağlı Sayfa</th>
                    <th>Durum</th>
                    <th>İşlemler</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <!-- Menü Ekle & Düzenle Modal -->
    <div class="modal fade" id="menuModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Menü Bilgileri</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="menuForm">
                        @csrf
                        <input type="hidden" id="menu_id">
                        <div class="mb-3">
                            <label>Üst Menü</label>
                            <select class="form-control" name="parent_id" id="parent_id">
                                <option value="">Ana Menü</option>
                                @foreach($menus as $menu)
                                    <option value="{{ $menu->id }}">{{ $menu->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Menü Adı</label>
                            <input type="text" class="form-control" name="name" id="name" required>
                        </div>
                        <div class="mb-3">
                            <label>URL</label>
                            <input type="text" class="form-control" name="url" id="url">
                        </div>
                        <div class="mb-3">
                            <label>Bağlı Sayfa</label>
                            <select class="form-control" name="page_id" id="page_id">
                                <option value="">Bağlı Sayfa Seç</option>
                                @foreach($pages as $page)
                                    <option value="{{ $page->id }}">{{ $page->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Durum</label>
                            <select class="form-control" name="is_active" id="is_active">
                                <option value="1">Aktif</option>
                                <option value="0">Pasif</option>
                            </select>
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
            // DataTable Initialization
            let table = $('#menusTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.menus.data') }}',
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'parent_name', name: 'parent_name'},
                    {data: 'name', name: 'name'},
                    {data: 'page_title', name: 'page_title'},
                    {data: 'is_active', name: 'is_active'},
                    {data: 'actions', name: 'actions', orderable: false, searchable: false}
                ]
            });

            // Yeni Menü Ekle Butonu
            $('#addMenuBtn').click(function () {
                $('#menuForm')[0].reset();
                $('#menu_id').val('');
                $('#menuModal').modal('show');
            });

            // Menü Düzenleme
            $(document).on('click', '.edit-menu', function () {
                let menuId = $(this).data('id');

                $.get(`/admin/menus/${menuId}/edit`, function (data) {
                    $('#menu_id').val(data.id);
                    $('#name').val(data.name);
                    $('#url').val(data.url);
                    $('#parent_id').val(data.parent_id);
                    $('#page_id').val(data.page_id);
                    $('#is_active').val(data.is_active ? 1 : 0);
                    $('#menuModal').modal('show');
                });
            });

            // Menü Formu Gönderimi (Kaydetme ve Güncelleme)
            $('#menuForm').submit(function (e) {
                e.preventDefault();

                let menuId = $('#menu_id').val();
                let url = menuId ? `/admin/menus/${menuId}` : '/admin/menus';
                let method = menuId ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    method: method,
                    data: $(this).serialize(),
                    success: function (response) {
                        $('#menuModal').modal('hide');
                        table.ajax.reload();
                        Swal.fire('Başarılı', response.message, 'success');
                    },
                    error: function () {
                        Swal.fire('Hata', 'Bir hata oluştu, lütfen tekrar deneyin.', 'error');
                    }
                });
            });

            // Menü Silme
            $(document).on('click', '.delete-menu', function () {
                let menuId = $(this).data('id');

                Swal.fire({
                    title: 'Emin misiniz?',
                    text: "Bu menüyü silmek istediğinize emin misiniz?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Evet, sil!',
                    cancelButtonText: 'Hayır'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/admin/menus/${menuId}`,
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
