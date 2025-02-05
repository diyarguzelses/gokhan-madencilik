@extends('admin.layouts.app')

@section('content')
    <div class="alert alert-primary mt-2">
        <strong>Menü Yönetimi:</strong> Buradan sitenizin ana ve alt menülerini oluşturabilir, güncelleyebilir ve silebilirsiniz.
        <ul class="mb-0">
            <li>Yeni bir menü eklemek için <strong>"Yeni Menü Ekle"</strong> butonuna tıklayın.</li>
            <li>Menü oluştururken <strong>"Ana Menü"</strong> veya <strong>"Alt Menü"</strong> seçebilirsiniz.</li>
            <li>Menü bir sayfaya bağlanabilir veya özel bir URL belirlenebilir. <strong>İkisi birden seçilemez!</strong></li>
            <li>Menü durumu "Aktif" veya "Pasif" olarak ayarlanabilir.</li>
            <li>Mevcut menüleri düzenlemek için <strong>"Düzenle"</strong>, silmek için <strong>"Sil"</strong> butonlarını kullanabilirsiniz.</li>
        </ul>
    </div>
    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between">
            <span class="fw-bold fs-5">Menü Yönetimi</span>
            <button class="btn btn-light text-primary fw-bold" id="addMenuBtn">
                <i class="bi bi-plus-circle"></i> Yeni Menü Ekle
            </button>
        </div>
        <div class="card-body">
            <table id="menusTable" class="table table-bordered">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Menü Türü</th>
                    <th>Adı</th>
                    <th>Bağlı Sayfa / URL</th>
                    <th>Durum</th>
                    <th>İşlemler</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <!-- Menü Ekle & Düzenle Modal -->
    <div class="modal fade" id="menuModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Menü Bilgileri</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="menuForm">
                        @csrf
                        <input type="hidden" id="menu_id">

                        <div class="mb-3">
                            <label>Menü Türü</label>
                            <select class="form-control" name="menu_type" id="menu_type">
                                <option value="main">Ana Menü</option>
                                <option value="submenu">Alt Menü</option>
                            </select>
                        </div>

                        <div class="mb-3 d-none" id="parentMenuContainer">
                            <label>Üst Menü</label>
                            <select class="form-control" name="parent_id" id="parent_id">
                                <option value="">Seçiniz</option>
                                @foreach($menus as $menu)
                                    <option value="{{ $menu->id }}">{{ $menu->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Menü Adı</label>
                            <input type="text" class="form-control" name="name" id="name" required>
                        </div>

                        <div class="mb-3">
                            <label>Bağlı Sayfa</label>
                            <select class="form-control" name="page_id" id="page_id">
                                <option value="">Seçiniz</option>
                                @foreach($pages as $page)
                                    <option value="{{ $page->id }}">{{ $page->title }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Eğer yeni bir sayfa eklemek istiyorsanız, burada tasarımını oluşturduğunuz sayfayı seçiniz.</small>
                        </div>

                        <div class="mb-3">
                            <label>URL</label>
                            <select class="form-control" name="url" id="url">
                                <option value="">Seçiniz...</option>
                                <option value="/communication">İletişim</option>
                                <option value="/completedProjects">Tamamlanan Projeler</option>
                                <option value="/continuingProjects">Devam Eden Projeler</option>
                                <option value="/machinePark">Makine Parkı</option>
                                <option value="/news">Haberler</option>
                            </select>
                            <small class="text-muted">Eğer sayfanız mevcut tasarımı olan sayfalardan biriyse, buradan ilgili URL’yi seçiniz.</small>
                        </div>


                        <div class="mb-3">
                            <label>Durum</label>
                            <select class="form-control" name="is_active" id="is_active">
                                <option value="1">Aktif</option>
                                <option value="0">Pasif</option>
                            </select>
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
            let table = $('#menusTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.menus.data') }}',
                language: {
                    url: "{{ asset('assets/datatables/turkish.json') }}"
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'menu_type', name: 'menu_type'},
                    {data: 'name', name: 'name'},
                    {
                        data: 'linked_content',
                        name: 'linked_content',
                        orderable: false,
                        searchable: false
                    },
                    {data: 'is_active', name: 'is_active'},
                    {data: 'actions', name: 'actions', orderable: false, searchable: false}
                ]
            });

            // Menü Tipine Göre Alanları Göster/Gizle
            $('#menu_type').change(function () {
                if ($(this).val() === 'submenu') {
                    $('#parentMenuContainer').removeClass('d-none');
                } else {
                    $('#parentMenuContainer').addClass('d-none');
                    $('#parent_id').val('');
                }
            });

            // URL ve Bağlı Sayfa Kontrolü
            $('#url, #page_id').on('input change', function () {
                if ($(this).attr('id') === 'url' && $(this).val().trim() !== '') {
                    $('#page_id').val('');
                } else if ($(this).attr('id') === 'page_id' && $(this).val() !== '') {
                    $('#url').val('');
                }
            });

            // Yeni Menü Ekle Butonu
            $('#addMenuBtn').click(function () {
                $('#menuForm')[0].reset();
                $('#menu_id').val('');
                $('#menuModal').modal('show');
            });

            // Menü Düzenleme
            $(document).on('click', '.edit-menu', function () {
                let menuId = $(this).data('id');

                $.get(`/admin/menus/${menuId}/edit`, function (data) {
                    $('#menu_id').val(data.id);
                    $('#name').val(data.name);
                    $('#url').val(data.url);
                    $('#page_id').val(data.page_id);
                    $('#is_active').val(data.is_active ? 1 : 0);
                    $('#menu_type').val(data.parent_id ? 'submenu' : 'main').trigger('change');
                    $('#parent_id').val(data.parent_id);
                    $('#menuModal').modal('show');
                });
            });

            // Menü Kaydetme & Güncelleme
            $('#menuForm').submit(function (e) {
                e.preventDefault();

                let menuId = $('#menu_id').val();
                let url = $('#url').val().trim();
                let pageId = $('#page_id').val();

                // Kullanıcı hem URL hem de bağlı sayfa seçmişse uyarı ver
                if (url !== '' && pageId !== '') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Geçersiz Seçim!',
                        text: 'Lütfen ya URL ya da Bağlı Sayfa seçiniz. İkisini aynı anda seçemezsiniz.',
                        confirmButtonText: 'Tamam',
                    });
                    return; // Formu durdur
                }

                let requestUrl = menuId ? `/admin/menus/${menuId}` : '/admin/menus';
                let method = menuId ? 'PUT' : 'POST';

                $.ajax({
                    url: requestUrl,
                    method: method,
                    data: $(this).serialize(),
                    success: function (response) {
                        $('#menuModal').modal('hide');
                        table.ajax.reload();
                        Swal.fire('Başarılı', response.message, 'success');
                    },
                    error: function () {
                        Swal.fire('Hata', 'Bir hata oluştu, lütfen tekrar deneyin.', 'error');
                    }
                });
            });

            // Menü Silme
            $(document).on('click', '.delete-menu', function () {
                let menuId = $(this).data('id');

                Swal.fire({
                    title: 'Emin misiniz?',
                    text: "Bu menüyü silmek istediğinize emin misiniz?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Evet, sil!',
                    cancelButtonText: 'Hayır'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/admin/menus/${menuId}`,
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
