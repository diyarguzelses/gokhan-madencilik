@extends('admin.layouts.app')

@section('content')
    <div class="alert alert-primary mt-2">
        <h5>Makine Parkı Yönetimi Hakkında</h5>
        <p>Bu panel, makine parkında bulunan makinelerin eklenmesi, düzenlenmesi ve silinmesi için kullanılır. Makineler, isimleri, adetleri ve görselleri ile sisteme kaydedilir.</p>

        <h6>Girdi Alanları:</h6>
        <ul>
            <li><strong>Makine Adı:</strong> Makinenin ismini belirten zorunlu bir alan.</li>
            <li><strong>Adet:</strong> Makinenin parkta kaç adet bulunduğunu gösteren sayısal bir alan (minimum 1).</li>
            <li><strong>Görsel:</strong> Opsiyonel bir alan olup, makinenin bir görselini yüklemeyi sağlar.</li>
        </ul>
    </div>

    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between" style="background: linear-gradient(135deg, #1e3c72, #2a5298); color: white; border-radius: 10px 10px 0 0;">
            <span class="fw-bold fs-5">Makine Parkı</span>
            <button class="btn btn-light text-primary fw-bold" id="addMachineBtn">
                <i class="bi bi-plus-circle"></i> Yeni Makine Ekle
            </button>
        </div>
        <div class="card-body">
            <table id="machinesTable" class="table table-bordered">
                <thead><br>
                <tr>
                    <th>ID</th>
                    <th>Makine Adı</th>
                    <th>Adet</th>
                    <th>Görsel</th>
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
                    <h5 class="modal-title" id="modalTitle">Yeni Makine Ekle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="machineForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="machine_id">
                        <div class="mb-3">
                            <label>Makine Adı</label>
                            <input type="text" class="form-control" name="name" id="name" required>
                        </div>
                        <div class="mb-3">
                            <label>Adet</label>
                            <input type="number" class="form-control" name="quantity" id="quantity" required min="1">
                        </div>
                        <div class="mb-3">
                            <label>Görsel</label>
                            <input type="file" class="form-control" name="image" id="image">
                            <!-- Resim container -->
                            <div id="imageContainer" style="position: relative; display: inline-block;">
                                <img id="previewImage" src="" class="mt-2 img-thumbnail" style="max-width: 100px; display: none;">
                                <!-- Çarpı simgesi: görsel silme -->
                                <span id="deleteImageIcon" style="position: absolute; top:20px; right: 0px; background: red; color: white; padding: 6px 12px;border-radius: 5px ; cursor: pointer; display: none;">&times;</span>
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
    <script>
        $(document).ready(function () {
            let table = $('#machinesTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.machines.data') }}',
                language: {
                    url: "{{ asset('assets/datatables/turkish.json') }}"
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'quantity', name: 'quantity'},
                    {data: 'image', name: 'image', orderable: false, searchable: false},
                    {data: 'actions', name: 'actions', orderable: false, searchable: false}
                ]
            });

            // Yeni Makine Ekle Butonu
            $('#addMachineBtn').click(function () {
                $('#machineForm')[0].reset();
                $('#machine_id').val('');
                $('#previewImage').hide();
                $('#deleteImageIcon').hide();
                $('#machineModal').modal('show');
            });

            // Düzenleme Butonu
            $(document).on('click', '.edit-machine', function () {
                let machineId = $(this).data('id');

                $.get(`/admin/machines/${machineId}/edit`, function (data) {
                    $('#machine_id').val(data.id);
                    $('#name').val(data.name);
                    $('#quantity').val(data.quantity);
                    if (data.image) {
                        $('#previewImage').attr('src', '/' + data.image).show();
                        // Eğer düzenlemede resim varsa, çarpı simgesini göster
                        $('#deleteImageIcon').show().data('id', data.id);
                    } else {
                        $('#previewImage').hide();
                        $('#deleteImageIcon').hide();
                    }
                    $('#machineModal').modal('show');
                });
            });

            // Form Gönderme (Yeni Kayıt veya Güncelleme)
            $('#machineForm').submit(function (e) {
                e.preventDefault();
                let formData = new FormData(this);
                let machineId = $('#machine_id').val();
                let url = machineId ? `/admin/machines/${machineId}` : '/admin/machines';

                if (machineId) {
                    formData.append('_method', 'PUT');
                }

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        $('#machineModal').modal('hide');
                        table.ajax.reload();
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

            // Görsel Önizleme
            $('#image').change(function (event) {
                let reader = new FileReader();
                reader.onload = function (e) {
                    $('#previewImage').attr('src', e.target.result).show();
                    // Yeni resim seçildiğinde, çarpı simgesini gizleyelim
                    $('#deleteImageIcon').hide();
                };
                reader.readAsDataURL(event.target.files[0]);
            });

            // Görsel Silme (Çarpı simgesine tıklandığında, onay sormadan)
            $(document).on('click', '#deleteImageIcon', function () {
                let machineId = $(this).data('id');
                $.ajax({
                    url: `/admin/machines/delete-image/${machineId}`,
                    method: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function (response) {
                        if(response.success) {
                            $('#previewImage').attr('src', '').hide();
                            $('#deleteImageIcon').hide();
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

    </script>
@endsection
