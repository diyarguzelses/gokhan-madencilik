@extends('admin.layouts.app')
@section('content')
    <div class="alert alert-primary mt-2">
        <h5>Haberler Yönetimi Hakkında</h5>
        <p>Bu panel, web sitesinde yayınlanacak haberlerin eklenmesi, düzenlenmesi ve silinmesi için kullanılır. Haberler başlık, içerik ve görsel ile birlikte kaydedilir.</p>
        <h6>Girdi Alanları:</h6>
        <ul>
            <li><strong>Başlık:</strong> Haberin başlığını belirten zorunlu bir alan.</li>
            <li><strong>İçerik:</strong> Haberin detaylı içeriğini içeren zorunlu bir alan.</li>
            <li><strong>Resim:</strong> Opsiyonel bir alan olup, habere bir görsel eklemeyi sağlar.</li>
        </ul>
    </div>

    <div class="alert alert-primary mt-2">
        <h5>Ek bilgi</h5>
        <p>
            Yeşil arka plan, anasayfada gösterilen haberi ifade eder. Ayrıca haberleri sürükleyerek sıralayabilir, düzenleyebilir veya silebilirsiniz.
        </p>
    </div>

    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between" style="background: linear-gradient(135deg, #1e3c72, #2a5298); color: white; border-radius: 10px 10px 0 0;">
            <span class="fw-bold fs-5">Haberler</span>
            <!-- Yeni Haber Ekle butonuna tıklanınca create sayfasına yönlendiriliyor -->
            <a href="{{ route('admin.news.create') }}" class="btn btn-light text-primary fw-bold">
                <i class="bi bi-plus-circle"></i> Yeni Haber Ekle
            </a>
        </div>
        <div class="card-body mt-5">
            <table id="newsTable" class="table table-bordered">
                <thead>
                <tr>
                    <th>Sıra</th>
                    <th>ID</th>
                    <th>Başlık</th>
                    <th>İçerik</th>
                    <th>Resim</th>
                    <th>İşlemler</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
            <small class="text-muted">Satırları tutup sürükleyerek haberlerin sırasını değiştirebilirsiniz.</small>
        </div>
    </div>
@endsection

@section('script')
    <!-- jQuery UI Sortable CDN -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script>
        $(document).ready(function () {
            let table = $('#newsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route("admin.news.data") }}',
                language: {
                    url: "{{ asset('assets/datatables/turkish.json') }}"
                },
                columns: [
                    { data: 'order', name: 'order' },
                    { data: 'id', name: 'id' },
                    { data: 'title', name: 'title' },
                    {
                        data: 'content',
                        name: 'content',
                        render: function(data) {
                            let div = document.createElement("div");
                            div.innerHTML = data;
                            let plainText = div.textContent || div.innerText || "";
                            return plainText.length > 50 ? plainText.substring(0, 50) + '...' : plainText;
                        }
                    },
                    { data: 'image', name: 'image', orderable: false, searchable: false },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false }
                ],
                createdRow: function (row, data, dataIndex) {
                    if (data.frontpage) {
                        $(row).find('td').not(':last-child').css('background-color', '#90ee90');
                    }
                }
            });

            // Her çizimde satırlara data-id attribute ekleyelim
            table.on('draw.dt', function() {
                $('#newsTable tbody tr').each(function() {
                    var data = table.row(this).data();
                    if (data && data.id) {
                        $(this).attr('data-id', data.id);
                    }
                });
            });

            // jQuery UI Sortable: Satırları sürükleyerek sıralama
            $("#newsTable tbody").sortable({
                helper: function(e, tr) {
                    tr.children().each(function() {
                        $(this).width($(this).width());
                    });
                    return tr;
                },
                update: function(event, ui) {
                    let orders = [];
                    $("#newsTable tbody tr").each(function(index) {
                        let newsId = $(this).attr('data-id');
                        if (newsId) {
                            orders.push({ id: newsId, order: index + 1 });
                        }
                    });
                    if (orders.length === 0) {
                        Swal.fire({
                            title: 'Uyarı',
                            text: 'Sıralanacak haber bulunamadı.',
                            icon: 'warning'
                        });
                        return;
                    }
                    $.ajax({
                        url: "{{ route('admin.news.news-updateOrder') }}",
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
                                text: 'Haber sırası güncellenirken bir hata oluştu.',
                                icon: 'error'
                            });
                        }
                    });
                }
            });

            // Haber düzenleme butonuna tıklanıldığında edit sayfasına yönlendir
            $(document).on('click', '.edit-news', function () {
                let newsId = $(this).data('id');
                window.location.href = `/FT23BA23DG12/news/${newsId}/edit`;
            });

            // Haber silme işlemi
            $(document).on('click', '.delete-news', function () {
                let newsId = $(this).data('id');
                Swal.fire({
                    title: 'Emin misiniz?',
                    text: "Bu haberi silmek istediğinize emin misiniz?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Evet, sil!',
                    cancelButtonText: 'Hayır'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/FT23BA23DG12/news/${newsId}`,
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

            // Toggle Frontpage işlemi
            $(document).on('click', '.toggle-frontpage', function () {
                let newsId = $(this).data('id');
                $.ajax({
                    url: `/FT23BA23DG12/news/toggle-frontpage/${newsId}`,
                    method: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function (response) {
                        if (response.success) {
                            Swal.fire('Başarılı', response.message, 'success');
                            $('#newsTable').DataTable().ajax.reload(null, false);
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
