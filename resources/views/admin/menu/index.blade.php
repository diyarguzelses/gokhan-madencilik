@extends('admin.layouts.app')

@section('content')
    <div class="card mb-5 mb-xl-8">
        <!-- Kart Başlık -->
        <div class="card-header border-0 pt-5 pb-5 px-4 d-flex align-items-center justify-content-between"
             style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); color: white; border-radius: 10px 10px 0 0;">
            <div>
                <span class="fw-bold fs-2 mb-3">Menü Yönetimi</span><br>

                <span class="fw-light fs-5">Web sitenizin menülerini buradan oluşturabilir, düzenleyebilir ve silebilirsiniz.</span>
            </div>
            <button class="btn btn-light text-primary fw-semibold" id="addMenuButton">
                <i class="bi bi-plus-circle me-2"></i> Yeni Menü Oluştur
            </button>
        </div>

        <!-- Kart İçerik -->
        <div class="card-body py-3">
            <div class="table-responsive">
                <table class="table table-row-bordered table-striped align-middle gs-0 gy-3" id="menusTable">
                    <thead class="fw-bold text-muted bg-light">
                    <tr>
                        <th>#</th>
                        <th>Adı</th>
                        <th>Bağlantı</th>
                        <th>Durum</th>
                        <th>İşlemler</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal: Menü Ekle/Düzenle -->
    <div class="modal fade" id="menuModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="menuForm">
                    @csrf
                    <input type="hidden" name="_method" id="method">
                    <div class="modal-header" >
                        <h5 class="modal-title" id="menuModalLabel"></h5>
                        <button type="button" class="btn-close " data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="menuName" class="form-label">Menü Adı</label>
                            <input type="text" class="form-control" id="menuName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="menuUrl" class="form-label">Bağlantı</label>
                            <input type="url" class="form-control" id="menuUrl" name="url" required>
                        </div>
                        <div class="mb-3">
                            <label for="menuStatus" class="form-label">Durum</label>
                            <select class="form-select" id="menuStatus" name="is_active" required>
                                <option value="1">Aktif</option>
                                <option value="0">Pasif</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                        <button type="submit" class="btn btn-primary">Kaydet</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            const table = $('#menusTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.menus.data') }}',
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'url', name: 'url'},
                    {data: 'status', name: 'status', orderable: false, searchable: false},
                    {data: 'actions', name: 'actions', orderable: false, searchable: false},
                ],
                language: {
                },
            });

            $('#addMenuButton').click(function () {
                $('#menuForm')[0].reset();
                $('#menuModalLabel').text('Yeni Menü Ekle');
                $('#method').val('POST');
                $('#menuForm').attr('action', '{{ route('admin.menus.store') }}');
                $('#menuModal').modal('show');
            });

            $('#menusTable').on('click', '.editMenuButton', function () {
                const id = $(this).data('id');
                const name = $(this).data('name');
                const url = $(this).data('url');
                const active = $(this).data('active');

                $('#menuForm')[0].reset();
                $('#menuModalLabel').text('Menü Düzenle');
                $('#method').val('PUT');
                $('#menuName').val(name);
                $('#menuUrl').val(url);
                $('#menuStatus').val(active);
                $('#menuForm').attr('action', `/menus/${id}`);
                $('#menuModal').modal('show');
            });

            $('#menusTable').on('click', '.deleteMenuButton', function () {
                const id = $(this).data('id');
                Swal.fire({
                    title: 'Emin misiniz?',
                    text: "Bu işlem geri alınamaz!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Evet, sil!',
                    cancelButtonText: 'Hayır',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/menus/${id}`,
                            type: 'DELETE',
                            data: { _token: '{{ csrf_token() }}' },
                            success: function () {
                                table.ajax.reload();
                                Swal.fire(
                                    'Silindi!',
                                    'Menü başarıyla silindi.',
                                    'success',

                                );
                            },
                            error: function () {
                                Swal.fire(
                                    'Hata!',
                                    'Bir sorun oluştu, lütfen tekrar deneyin.',
                                    'error',

                                );
                            }
                        });
                    }
                });
            });

            $('#menuForm').submit(function (e) {
                e.preventDefault();
                const method = $('#method').val();
                const action = $(this).attr('action');

                $.ajax({
                    url: action,
                    type: method,
                    data: $(this).serialize(),
                    success: function () {
                        $('#menuModal').modal('hide');
                        table.ajax.reload();
                        Swal.fire({
                            title: 'Başarılı!',
                            text: 'Menü başarıyla kaydedildi.',
                            icon: 'success',
                            confirmButtonText: 'Tamam',
                        });
                        table.ajax.reload();
                    },
                    error: function () {
                        Swal.fire({
                            title: 'Hata!',
                            text: 'Bir sorun oluştu, lütfen tekrar deneyin.',
                            icon: 'error',
                            confirmButtonText: 'Tamam',
                        });
                    }
                });
            });
        });
    </script>
@endsection
