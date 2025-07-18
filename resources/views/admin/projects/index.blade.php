@extends('admin.layouts.app')

@section('content')
    <div class="alert alert-primary mt-2">
        <h5>Projeler Yönetimi Hakkında</h5>
        <p>Bu panel, web sitesinde listelenecek projelerin eklenmesi, düzenlenmesi ve silinmesi için kullanılır. Projeler, belirli kategorilere atanarak düzenlenebilir.</p>

        <h6>Girdi Alanları:</h6>
        <ul>
            <li><strong>Proje Adı:</strong> Projeye verilen ismi içerir, zorunludur.</li>
            <li><strong>Kategori:</strong> Projenin ait olduğu kategori seçilmelidir.</li>
            <li><strong>Açıklama:</strong> Projeye dair kısa bir bilgi girilmelidir.</li>
            <li><strong>Durumu:</strong> Projeye dair durum bilgisi girilmelidir.</li>
            <li><strong>Resimler:</strong> Projeye dair resimler girilmelidir.</li>
        </ul>
    </div>

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
                        <th>Sıra</th>
                        <th>#</th>
                        <th>Adı</th>
                        <th>Kategori</th>
                        <th>Açıklama</th>
                        <th>İşlemler</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <small class="text-muted">Satırları tutup sürükleyerek projelerin sırasını değiştirebilirsiniz.</small>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- jQuery UI Sortable CDN -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script>
        $(document).ready(function () {
            // DataTable Initialization
            const table = $('#projectsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.projects.data') }}',
                language: {
                    url: "{{ asset('assets/datatables/turkish.json') }}"
                },
                order: [[0, 'asc']],
                columns: [
                    { data: 'order', name: 'order' },
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'category_name', name: 'category_name' },
                    {
                        data: 'description',
                        name: 'description',
                        render: function(data) {
                            // HTML etiketlerini kaldırıyoruz
                            var div = document.createElement('div');
                            div.innerHTML = data;
                            var plainText = div.textContent || div.innerText || "";
                            return plainText.length > 50 ? plainText.substr(0, 50) + '...' : plainText;
                        }
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            // Her çizimde satırlara data-id attribute ekliyoruz
            table.on('draw.dt', function() {
                $('#projectsTable tbody tr').each(function() {
                    var data = table.row(this).data();
                    if (data && data.id) {
                        $(this).attr('data-id', data.id);
                    }
                });
            });

            // jQuery UI Sortable: Satırları sürükleyerek sıralama
            $("#projectsTable tbody").sortable({
                helper: function(e, tr) {
                    tr.children().each(function() {
                        $(this).width($(this).width());
                    });
                    return tr;
                },
                update: function(event, ui) {
                    let orders = [];
                    $("#projectsTable tbody tr").each(function(index) {
                        let projectId = $(this).attr('data-id');
                        if (projectId) {
                            // Sıralama 1'den başlasın
                            orders.push({ id: projectId, order: index + 1 });
                        }
                    });
                    if (orders.length === 0) {
                        Swal.fire({
                            title: 'Uyarı',
                            text: 'Sıralanacak proje bulunamadı.',
                            icon: 'warning'
                        });
                        return;
                    }
                    // AJAX ile yeni sıralamayı gönder
                    $.ajax({
                        url: "{{ route('admin.projects.order-updateOrder') }}",
                        method: "POST",
                        data: JSON.stringify({
                            _token: "{{ csrf_token() }}",
                            orders: orders
                        }),
                        contentType: "application/json; charset=utf-8",
                        dataType: "json",
                        success: function(response) {
                            Swal.fire({
                                title: 'Başarılı',
                                text: response.message,
                                icon: 'success'
                            }).then(function() {
                                table.ajax.reload();
                            });
                        },
                        error: function() {
                            Swal.fire({
                                title: 'Hata',
                                text: 'Proje sırası güncellenirken bir hata oluştu.',
                                icon: 'error'
                            });
                        }
                    });
                }
            });

            // Proje Silme İşlemi
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
                            url: `/FT23BA23DG12/projects/delete/${projectId}`,
                            method: 'DELETE',
                            data: { _token: '{{ csrf_token() }}' },
                            success: function (response) {
                                if (response.success) {
                                    table.ajax.reload();
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
