@extends('admin.layouts.app')

@section('content')
    <div class="alert alert-primary mt-2">
        <h5>Sektörler Yönetimi Hakkında</h5>
        <p>Bu panel, web sitesinde listelenecek sektörlerin eklenmesi, düzenlenmesi ve silinmesi için kullanılır.</p>
        <h6>Girdi Alanları:</h6>
        <ul>
            <li><strong>Sektör Adı:</strong> Sektör ismi, zorunludur.</li>
            <li><strong>Açıklama:</strong> Sektöre dair kısa bilgi girilmelidir.</li>
            <li><strong>Resim:</strong> Sektöre dair görsel, opsiyoneldir.</li>
        </ul>
    </div>

    <div class="card mb-5">
        <!-- Kart Başlık -->
        <div class="card-header text-white d-flex justify-content-between"
             style="background: linear-gradient(135deg, #1e3c72, #2a5298); border-radius: 10px 10px 0 0;">
            <span class="fw-bold fs-5">Sektörler</span>
            <button class="btn btn-light text-primary fw-bold" id="addSectorBtn">
                <i class="bi bi-plus-circle"></i> Yeni Sektör Ekle
            </button>
        </div>
        <!-- Kart İçerik -->
        <div class="card-body">
            <table id="sectorsTable" class="table table-bordered">
                <thead>
                <tr>
                    <th>Sıra</th>
                    <th>ID</th>
                    <th>Adı</th>
                    <th>Açıklama</th>
                    <th>Resim</th>
                    <th>İşlemler</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
            <small class="text-muted">Satırları tutup sürükleyerek sektör sıralamasını değiştirebilirsiniz.</small>
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
                            <input type="text" class="form-control" id="name">
                        </div>
                        <div class="mb-3">
                            <label>Açıklama</label>
                            <!-- required kaldırıldı -->
                            <textarea class="form-control" id="text" name="text"></textarea>
                        </div>
                        <div class="mb-3 position-relative">
                            <label>Resim</label>
                            <input type="file" class="form-control" id="image" accept="image/*">
                            <div id="imageContainer" style="position: relative; display: inline-block;">
                                <img id="previewImage" src="/images/default-placeholder.png" class="img-fluid mt-4" style="max-height: 200px; display: none;">
                                <span id="deleteImageIcon" style="position: absolute; top:20px; right: 0px; background: red; color: white; padding: 6px 12px; border-radius: 5px; cursor: pointer; display: none;">&times;</span>
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
    <!-- CKEditor 5 Classic Editor CDN -->
    <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
    <!-- jQuery UI Sortable CDN -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script>
        $(document).ready(function () {
            // CKEditor Başlatma: "Açıklama" alanı için
            ClassicEditor
                .create(document.querySelector('#text'))
                .then(editor => {
                    window.sectorEditor = editor;
                })
                .catch(error => {
                    console.error('CKEditor yüklenirken hata oluştu:', error);
                });

            let table = $('#sectorsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.sectors.data') }}',
                language: {
                    url: "{{ asset('assets/datatables/turkish.json') }}"
                },
                columns: [
                    { data: 'order', name: 'order' },
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    {
                        data: 'text',
                        name: 'text',
                        render: function(data) {
                            let div = document.createElement('div');
                            div.innerHTML = data;
                            let plainText = div.textContent || div.innerText || "";
                            return plainText.length > 50 ? plainText.substr(0, 50) + '...' : plainText;
                        }
                    },
                    {
                        data: 'image',
                        name: 'image',
                        orderable: false,
                        searchable: false,
                        render: function(data) {
                            let imageUrl = data ? `/uploads/sectors/${data}` : '/images/default-placeholder.png';
                            return `<img src="${imageUrl}" class="img-thumbnail" width="50">`;
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data) {
                            return `
                                <div class="d-flex align-items-center gap-2">
                                    <button class="btn btn-primary btn-sm edit-sector"
                                        data-id="${data.id}"
                                        data-name="${data.name}"
                                        data-text='${data.text}'
                                        data-image="${data.image}">
                                        <i class="bi bi-pencil"></i> Düzenle
                                    </button>
                                    <button class="btn btn-danger btn-sm delete-sector" data-id="${data.id}">
                                        <i class="bi bi-trash"></i> Sil
                                    </button>
                                </div>
                            `;
                        }
                    }
                ]
            });

            // DataTable her çizildiğinde satırlara data-id attribute ekleyelim
            table.on('draw.dt', function() {
                $('#sectorsTable tbody tr').each(function() {
                    var data = table.row(this).data();
                    if (data && data.id) {
                        $(this).attr('data-id', data.id);
                    }
                });
            });

            // jQuery UI Sortable: Satırları sürükleyerek sıralama
            $("#sectorsTable tbody").sortable({
                helper: function(e, tr) {
                    tr.children().each(function() {
                        $(this).width($(this).width());
                    });
                    return tr;
                },
                update: function(event, ui) {
                    let orders = [];
                    $("#sectorsTable tbody tr").each(function(index) {
                        let sectorId = $(this).attr('data-id');
                        if (sectorId) {
                            // Sıralama 1'den başlasın
                            orders.push({ id: sectorId, order: index + 1 });
                        }
                    });
                    if (orders.length === 0) {
                        Swal.fire({
                            title: 'Uyarı',
                            text: 'Sıralanacak sektör bulunamadı.',
                            icon: 'warning'
                        });
                        return;
                    }
                    // AJAX ile yeni sıralamayı gönder
                    $.ajax({
                        url: "{{ route('admin.sectors.updateOrder') }}",
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
                                text: 'Sektör sırası güncellenirken bir hata oluştu.',
                                icon: 'error'
                            });
                        }
                    });
                }
            });

            // Yeni Sektör Ekle Butonu
            $('#addSectorBtn').click(function () {
                $('#sectorForm')[0].reset();
                $('#sector_id').val('');
                $('#previewImage').hide();
                $('#deleteImageIcon').hide();
                if (window.sectorEditor) {
                    window.sectorEditor.setData('');
                }
                $('#sectorModal').modal('show');
            });

            // Resim Yüklerken Önizleme
            $('#image').change(function () {
                let reader = new FileReader();
                reader.onload = function (e) {
                    $('#previewImage').attr('src', e.target.result).show();
                    $('#deleteImageIcon').hide();
                };
                reader.readAsDataURL(this.files[0]);
            });

            // Sektör Düzenleme İşlemi
            $(document).on('click', '.edit-sector', function () {
                let sectorId = $(this).data('id');
                let name = $(this).data('name');
                let text = $(this).data('text');
                let image = $(this).data('image');

                $('#sector_id').val(sectorId);
                $('#name').val(name);
                if (window.sectorEditor) {
                    window.sectorEditor.setData(text);
                }
                let imageUrl = '/images/default-placeholder.png';
                if (image && image.trim() !== "") {
                    imageUrl = `/uploads/sectors/${image}`;
                    $('#deleteImageIcon').show().data('id', sectorId);
                } else {
                    $('#deleteImageIcon').hide();
                }
                $('#image').val('');
                $('#previewImage').attr('src', imageUrl).show();
                $('#sectorModal').modal('show');
            });

            // Form Gönderme (Yeni Kayıt veya Güncelleme)
            $('#sectorForm').submit(function (e) {
                e.preventDefault();
                $('#text').val(window.sectorEditor.getData());
                let sectorId = $('#sector_id').val();
                let formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('name', $('#name').val());
                formData.append('text', $('#text').val());
                let imageFile = $('#image')[0].files[0];
                if (imageFile) {
                    formData.append('image', imageFile);
                }
                let url = sectorId ? `/FT23BA23DG12/sectors/${sectorId}` : '/FT23BA23DG12/sectors';
                if (sectorId) {
                    formData.append('_method', 'PUT');
                }
                $.ajax({
                    url: url,
                    method: 'POST',
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

            // Sektör Silme İşlemi
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
                            url: `/FT23BA23DG12/sectors/${sectorId}`,
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

            // Mevcut Resim Silme İşlemi
            $(document).on('click', '#deleteImageIcon', function () {
                let sectorId = $(this).data('id');
                $.ajax({
                    url: `/FT23BA23DG12/sectors/delete-image/${sectorId}`,
                    method: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function (response) {
                        if (response.success) {
                            $('#previewImage').attr('src', '/images/default-placeholder.png');
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
