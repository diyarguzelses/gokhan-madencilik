@extends('admin.layouts.app')

@section('content')
    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between">
            <span class="fw-bold fs-5">Sektörler</span>
            <button class="btn btn-light text-primary fw-bold" id="addSectorBtn">
                <i class="bi bi-plus-circle"></i> Yeni Sektör Ekle
            </button>
        </div>
        <div class="card-body">
            <table id="sectorsTable" class="table table-bordered">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Adı</th>
                    <th>Açıklama</th>
                    <th>İşlemler</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <!-- Sektör Ekle & Düzenle Modal -->
    <div class="modal fade" id="sectorModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Sektör Bilgileri</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="sectorForm">
                        @csrf
                        <input type="hidden" id="sector_id">
                        <div class="mb-3">
                            <label>Sektör Adı</label>
                            <input type="text" class="form-control" id="name" required>
                        </div>
                        <div class="mb-3">
                            <label>Açıklama</label>
                            <textarea class="form-control" id="text" required></textarea>
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
            let table = $('#sectorsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.sectors.data') }}',
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'text', name: 'text'},
                    {data: 'actions', name: 'actions', orderable: false, searchable: false}
                ]
            });

            // Yeni Sektör Ekle Butonu
            $('#addSectorBtn').click(function () {
                $('#sectorForm')[0].reset();
                $('#sector_id').val('');
                $('#sectorModal').modal('show');
            });

            // Düzenleme Butonuna Basınca Verileri Modal'a Aktar
            $(document).on('click', '.edit-sector', function () {
                $('#sector_id').val($(this).data('id'));
                $('#name').val($(this).data('name'));
                $('#text').val($(this).data('text'));
                $('#sectorModal').modal('show');
            });

            // Form Gönderme (Yeni Kayıt veya Güncelleme)
            $('#sectorForm').submit(function (e) {
                e.preventDefault();
                let sectorId = $('#sector_id').val();
                let formData = {
                    _token: '{{ csrf_token() }}',
                    name: $('#name').val(),
                    text: $('#text').val()
                };

                let url = sectorId ? `/admin/sectors/${sectorId}` : '/admin/sectors';
                let method = sectorId ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    method: method,
                    data: formData,
                    success: function (response) {
                        table.ajax.reload();
                        $('#sectorModal').modal('hide');
                        Swal.fire('Başarılı', response.message, 'success');
                    },
                    error: function () {
                        Swal.fire('Hata', 'Bir hata oluştu, lütfen tekrar deneyin.', 'error');
                    }
                });
            });

            // Sektör Silme
            $(document).on('click', '.delete-sector', function () {
                let sectorId = $(this).data('id');

                Swal.fire({
                    title: 'Emin misiniz?',
                    text: "Bu sektörü silmek istediğinize emin misiniz?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Evet, sil!',
                    cancelButtonText: 'Hayır'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/admin/sectors/${sectorId}`,
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
