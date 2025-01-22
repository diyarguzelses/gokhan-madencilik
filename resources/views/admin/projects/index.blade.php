@extends('admin.layouts.app')

@section('content')
    <div class="card mb-5">
        <!-- Kart Başlık -->
        <div class="card-header border-0 pt-4 pb-4 px-4 d-flex align-items-center justify-content-between"
             style="background: linear-gradient(135deg, #1e3c72, #2a5298); color: white; border-radius: 10px 10px 0 0;">
            <div>
                <span class="fw-bold fs-4">Projeler</span><br>
                <span class="fw-light fs-6">Proje ekleme, düzenleme ve silme işlemlerini buradan gerçekleştirebilirsiniz.</span>
            </div>
            <a href="{{ route('admin.projects.create') }}" class="btn btn-light text-primary fw-semibold">
                <i class="bi bi-plus-circle me-2"></i> Yeni Proje Ekle
            </a>
        </div>

        <!-- Kart İçerik -->
        <div class="card-body py-3">
            <div class="table-responsive">
                <table id="projectsTable" class="table table-striped table-bordered">
                    <thead class="fw-bold text-muted bg-light">
                    <tr>
                        <th>#</th>
                        <th>Adı</th>
                        <th>Kategori</th>
                        <th>Açıklama</th>
                        <th>İşlemler</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            // DataTable Initialization
            const table = $('#projectsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.projects.data') }}',
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'category_name', name: 'category_name'},
                    {
                        data: 'description',
                        name: 'description',
                        render: function (data) {
                            return data.length > 50 ? data.substr(0, 50) + '...' : data;
                        }
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    }
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/tr.json',
                }
            });

            // Proje Silme
            $(document).on('click', '.delete-project', function () {
                const projectId = $(this).data('id');

                Swal.fire({
                    title: 'Emin misiniz?',
                    text: "Bu projeyi silmek istediğinize emin misiniz?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Evet, sil!',
                    cancelButtonText: 'Hayır'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/admin/projects/delete/${projectId}`,
                            method: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function (response) {
                                if (response.success) {
                                    table.ajax.reload(); // Tabloyu yenile
                                    Swal.fire(
                                        'Silindi!',
                                        'Proje başarıyla silindi.',
                                        'success'
                                    );
                                } else {
                                    Swal.fire(
                                        'Hata!',
                                        'Bir hata oluştu, lütfen tekrar deneyin.',
                                        'error'
                                    );
                                }
                            },
                            error: function () {
                                Swal.fire(
                                    'Hata!',
                                    'Bir hata oluştu, lütfen tekrar deneyin.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
