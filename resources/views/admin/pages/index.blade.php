@extends('admin.layouts.app')
@section('content')
    <div class="container mt-4">
        <div class="alert alert-primary mt-2">
            <h5>Sayfa Yönetimi Hakkında</h5>
            <p>Bu panel, adminlerin web sitesine yeni sayfalar eklemesini, mevcut sayfaları düzenlemesini ve silmesini sağlar.</p>
            <h6>Girdi Alanları:</h6>
            <ul>
                <li><strong>Sayfa Başlığı:</strong> Sayfanın başlığını belirten zorunlu bir alan.</li>
                <li><strong>İçerik:</strong> Sayfanın ana metin içeriğini içerir, zorunludur.</li>
                <li><strong>Görsel:</strong> Opsiyonel bir alan olup, sayfaya bir görsel yüklemeyi sağlar.</li>
            </ul>
        </div>

        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between" style="background: linear-gradient(135deg, #1e3c72, #2a5298); color: white; border-radius: 10px 10px 0 0;">
                <span class="fw-bold fs-5">Sayfa Yönetimi</span>
                <!-- Yeni Sayfa Ekle butonuna tıklanınca create sayfasına yönlendirme -->
                <a href="{{ route('admin.pages.create') }}" class="btn btn-light text-primary fw-bold">
                    <i class="bi bi-plus-circle"></i> Yeni Sayfa Ekle
                </a>
            </div>
            <div class="card-body">
                <table id="pagesTable" class="table table-bordered">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Başlık</th>
                        <th>İçerik</th>
                        <th>Görsel</th>
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
    <!-- DataTables ve SweetAlert gibi kütüphanelerin yüklü olduğunu varsayıyoruz -->
    <script>
        $(document).ready(function(){
            let table = $('#pagesTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route("admin.pages.data") }}',
                language: { url: "{{ asset('assets/datatables/turkish.json') }}" },
                columns: [
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
                    {
                        data: 'images',
                        name: 'images',
                        orderable: false,
                        searchable: false,
                        render: function(data) {
                            if(data && data.length > 0){
                                let html = '';
                                data.forEach(function(image){
                                    html += `<img src="/${image.image}" class="img-thumbnail me-1" width="50">`;
                                });
                                return html;
                            } else {
                                return 'Yok';
                            }
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data) {
                            return `
                            <div class="d-flex align-items-center gap-2">
                                <a href="/FT23BA23DG12/pages/${data.id}/edit" class="btn btn-primary btn-sm">
                                    <i class="bi bi-pencil"></i> Düzenle
                                </a>
                                <button class="btn btn-danger btn-sm delete-page" data-id="${data.id}">
                                    <i class="bi bi-trash"></i> Sil
                                </button>
                            </div>
                        `;
                        }
                    }
                ]
            });

            // Sayfa silme işlemi
            $(document).on('click', '.delete-page', function(){
                let pageId = $(this).data('id');
                Swal.fire({
                    title: 'Emin misiniz?',
                    text: "Bu sayfayı silmek istediğinize emin misiniz?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Evet, sil!',
                    cancelButtonText: 'Hayır'
                }).then((result)=>{
                    if(result.isConfirmed){
                        $.ajax({
                            url: `/FT23BA23DG12/pages/${pageId}`,
                            method: 'DELETE',
                            data: { _token: '{{ csrf_token() }}' },
                            success: function(response){
                                table.ajax.reload();
                                Swal.fire('Silindi!', response.message, 'success');
                            },
                            error: function(){
                                Swal.fire('Hata!', 'Bir hata oluştu, lütfen tekrar deneyin.', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
