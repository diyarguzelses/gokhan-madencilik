@extends('admin.layouts.app')

@section('content')
    <div class="alert alert-primary mt-2">
        <h5>Projelerin Kategorileri Hakkında</h5>
        <p>Bu panel, projeler için kategori ekleme, düzenleme ve silme işlemlerini yönetmek amacıyla kullanılır. Kategoriler, projeleri gruplamak ve düzenlemek için önemlidir.</p>

        <h6>Girdi Alanları:</h6>
        <ul>
            <li><strong>Kategori Adı:</strong> Projeye ait kategori adını belirten zorunlu bir alan.</li>
        </ul>
    </div>

    <div class="card mb-5">
        <!-- Kart Başlık -->
        <div class="card-header border-0 pt-4 pb-4 px-4 d-flex align-items-center justify-content-between"
             style="background: linear-gradient(135deg, #1e3c72, #2a5298); color: white; border-radius: 10px 10px 0 0;">
            <div>
                <span class="fw-bold fs-4">Kategoriler</span><br>
                <span class="fw-light fs-6">Kategori ekleme, düzenleme ve silme işlemlerini buradan gerçekleştirebilirsiniz.</span>
            </div>
            <button class="btn btn-light text-primary fw-semibold" id="addCategoryButton">
                <i class="bi bi-plus-circle me-2"></i> Yeni Kategori Ekle
            </button>
        </div>

        <!-- Kart İçerik -->
        <div class="card-body py-3">
            <div class="table-responsive">
                <table id="categoriesTable" class="table table-striped table-bordered">
                    <thead class="fw-bold text-muted bg-light"><br>
                    <tr>
                        <th>#</th>
                        <th>Adı</th>
                        <th>İşlemler</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal: Kategori Ekle/Düzenle -->
    <div class="modal fade" id="categoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="categoryForm">
                    @csrf
                    <input type="hidden" name="_method" id="method">
                    <div class="modal-header">
                        <h5 class="modal-title" id="categoryModalLabel"></h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Kategori Adı</label>
                            <input type="text" class="form-control" id="name" name="name" required>
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
            // DataTable Initialization
            const table = $('#categoriesTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.categories.data') }}',
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false}, // Sıra numarası
                    {data: 'name', name: 'name'},
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row) {
                            return `
                                <button class="btn btn-sm btn-primary edit-category"
                                    data-id="${row.id}" data-name="${row.name}">
                                    Düzenle
                                </button>
                                <button class="btn btn-sm btn-danger delete-category" data-id="${row.id}">
                                    Sil
                                </button>
                            `;
                        }
                    }
                ],
                language: {
                    url: "{{ asset('assets/datatables/turkish.json') }}" // Türkçe çeviri dosyasını yükle

                }
            });

            // Yeni Kategori Ekleme
            $('#addCategoryButton').click(function () {
                $('#categoryForm')[0].reset();
                $('#categoryModalLabel').text('Yeni Kategori Ekle');
                $('#method').val('POST');
                $('#categoryForm').attr('action', '{{ route('admin.categories.store') }}');
                $('#categoryModal').modal('show');
            });

            // Kategori Düzenleme
            $(document).on('click', '.edit-category', function () {
                const id = $(this).data('id');
                const name = $(this).data('name');

                $('#categoryForm')[0].reset();
                $('#categoryModalLabel').text('Kategori Düzenle');
                $('#method').val('post');
                $('#name').val(name);
                $('#categoryForm').attr('action', `/FT23BA23DG12/categories/${id}`);
                $('#categoryModal').modal('show');
            });

            // Kategori Silme
            $(document).on('click', '.delete-category', function () {
                const id = $(this).data('id');

                Swal.fire({
                    title: 'Emin misiniz?',
                    text: "Bu işlem geri alınamaz!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Evet, sil!',
                    cancelButtonText: 'Hayır'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/FT23BA23DG12/categories/${id}`,
                            method: 'DELETE',
                            data: {_token: '{{ csrf_token() }}'},
                            success: function () {
                                table.ajax.reload();
                                Swal.fire('Silindi!', 'Kategori başarıyla silindi.', 'success');
                            },
                            error: function () {
                                Swal.fire('Hata!', 'Bir hata oluştu, lütfen tekrar deneyin.', 'error');
                            }
                        });
                    }
                });
            });

            // Form Gönderimi
            $('#categoryForm').submit(function (e) {
                e.preventDefault();

                const method = $('#method').val();
                const action = $(this).attr('action');

                $.ajax({
                    url: action,
                    method: method,
                    data: $(this).serialize(),
                    success: function () {
                        $('#categoryModal').modal('hide');
                        table.ajax.reload();
                        Swal.fire('Başarılı!', 'Kategori başarıyla kaydedildi.', 'success');
                    },
                    error: function () {
                        Swal.fire('Hata!', 'Bir hata oluştu, lütfen tekrar deneyin.', 'error');
                    }
                });
            });
        });
    </script>
@endsection
