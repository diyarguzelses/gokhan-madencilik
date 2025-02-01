@extends('admin.layouts.app')

@section('content')
    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between">
            <span class="fw-bold fs-5">Makine Parkı</span>
            <button class="btn btn-light text-primary fw-bold" id="addMachineBtn">
                <i class="bi bi-plus-circle"></i> Yeni Makine Ekle
            </button>
        </div>
        <div class="card-body">
            <table id="machinesTable" class="table table-bordered">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Makine Adı</th>
                    <th>Adet</th>
                    <th>İşlemler</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <!-- Makine Ekle & Düzenle Modal -->
    <div class="modal fade" id="machineModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Makine Bilgileri</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="machineForm">
                        @csrf
                        <input type="hidden" id="machine_id">
                        <div class="mb-3">
                            <label>Makine Adı</label>
                            <input type="text" class="form-control" id="name" required>
                        </div>
                        <div class="mb-3">
                            <label>Adet</label>
                            <input type="number" class="form-control" id="quantity" required min="1">
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
            let table = $('#machinesTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.machines.data') }}',
                language: {
                    url: "{{ asset('assets/datatables/turkish.json') }}" // Türkçe çeviri dosyasını yükle
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'quantity', name: 'quantity'},
                    {data: 'actions', name: 'actions', orderable: false, searchable: false}
                ]
            });

            // Yeni Makine Ekle Butonu
            $('#addMachineBtn').click(function () {
                $('#machineForm')[0].reset();
                $('#machine_id').val('');
                $('#machineModal').modal('show');
            });

            // Düzenleme Butonuna Basınca Verileri Modal'a Aktar
            $(document).on('click', '.edit-machine', function () {
                $('#machine_id').val($(this).data('id'));
                $('#name').val($(this).data('name'));
                $('#quantity').val($(this).data('quantity'));
                $('#machineModal').modal('show');
            });

            // Form Gönderme (Yeni Kayıt veya Güncelleme)
            $('#machineForm').submit(function (e) {
                e.preventDefault();
                let machineId = $('#machine_id').val();
                let formData = {
                    _token: '{{ csrf_token() }}',
                    name: $('#name').val(),
                    quantity: $('#quantity').val()
                };

                let url = machineId ? `/admin/machines/${machineId}` : '/admin/machines';
                let method = machineId ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    method: method,
                    data: formData,
                    success: function (response) {
                        table.ajax.reload();
                        $('#machineModal').modal('hide');
                        Swal.fire('Başarılı', response.message, 'success');
                    },
                    error: function () {
                        Swal.fire('Hata', 'Bir hata oluştu, lütfen tekrar deneyin.', 'error');
                    }
                });
            });

            // Makine Silme
            $(document).on('click', '.delete-machine', function () {
                let machineId = $(this).data('id');

                Swal.fire({
                    title: 'Emin misiniz?',
                    text: "Bu makineyi silmek istediğinize emin misiniz?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Evet, sil!',
                    cancelButtonText: 'Hayır'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/admin/machines/${machineId}`,
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
