@extends('admin.layouts.app')

@section('content')
    <div class="card">
        <div class="card-header text-white d-flex justify-content-between" style="background: linear-gradient(135deg, #1e3c72, #2a5298); color: white; border-radius: 10px 10px 0 0;">
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
                    <th>Resim</th>
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
                    <form id="sectorForm" enctype="multipart/form-data">
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
                        <div class="mb-3">
                            <label>Resim</label>
                            <input type="file" class="form-control" id="image" accept="image/*">
                            <img id="previewImage" src="/images/default-placeholder.png" class="img-fluid mt-2"
                                 style="max-height: 200px; display: none;">
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
                language: {
                    url: "{{ asset('assets/datatables/turkish.json') }}"
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'text', name: 'text'},
                    {
                        data: 'image',
                        name: 'image',
                        orderable: false,
                        searchable: false,
                        render: function (data) {
                            let imageUrl = data ? `/uploads/sectors/${data}` : '/images/default-placeholder.png';
                            return `<img src="${imageUrl}" class="img-thumbnail" width="50">`;
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function (data) {
                            return `
                                <button class="btn btn-warning btn-sm edit-sector"
                                    data-id="${data.id}"
                                    data-name="${data.name}"
                                    data-text="${data.text}"
                                    data-image="${data.image}">
                                    <i class="bi bi-pencil"></i> Düzenle
                                </button>
                                <button class="btn btn-danger btn-sm delete-sector" data-id="${data.id}">
                                    <i class="bi bi-trash"></i> Sil
                                </button>
                            `;
                        }
                    }
                ]
            });

            // Yeni Sektör Ekle Butonu
            $('#addSectorBtn').click(function () {
                $('#sectorForm')[0].reset();
                $('#sector_id').val('');
                $('#previewImage').hide();
                $('#sectorModal').modal('show');
            });

            // Resim Yüklerken Önizleme
            $('#image').change(function () {
                let reader = new FileReader();
                reader.onload = function (e) {
                    $('#previewImage').attr('src', e.target.result).show();
                };
                reader.readAsDataURL(this.files[0]);
            });

            // Düzenleme Butonuna Basınca Verileri Modal'a Aktar
            $(document).on('click', '.edit-sector', function () {
                let image = $(this).data('image');

                console.log("Data-image:", image); // Konsolda kontrol et

                $('#sector_id').val($(this).data('id'));
                $('#name').val($(this).data('name'));
                $('#text').val($(this).data('text'));

                let imageUrl = image ? `/uploads/sectors/${image}` : '/images/default-placeholder.png';
                $('#previewImage').attr('src', imageUrl).show();

                $('#sectorModal').modal('show');
            });

            // Form Gönderme (Yeni Kayıt veya Güncelleme)
            $('#sectorForm').submit(function (e) {
                e.preventDefault();
                let sectorId = $('#sector_id').val();
                let formData = new FormData();

                formData.append('_token', '{{ csrf_token() }}');
                formData.append('name', $('#name').val());
                formData.append('text', $('#text').val());

                let imageFile = $('#image')[0].files[0];
                if (imageFile) {
                    formData.append('image', imageFile);
                }

                let url = sectorId ? `/admin/sectors/${sectorId}` : '/admin/sectors';
                let method = sectorId ? 'POST' : 'POST';

                if (sectorId) {
                    formData.append('_method', 'PUT');
                }

                $.ajax({
                    url: url,
                    method: method,
                    data: formData,
                    processData: false,
                    contentType: false,
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
                            data: {_token: '{{ csrf_token() }}'},
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
