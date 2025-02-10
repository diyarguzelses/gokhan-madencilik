@extends('admin.layouts.app')

@section('content')
    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between">
            <span class="fw-bold fs-5">Kullanıcılar</span>
            <button class="btn btn-light text-primary fw-bold" id="addUserBtn">
                <i class="bi bi-plus-circle"></i> Yeni Kullanıcı Ekle
            </button>
        </div>
        <div class="card-body">
            <table id="usersTable" class="table table-bordered">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Kullanıcı Adı</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>İşlemler</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <!-- Kullanıcı Ekle & Düzenle Modal -->
    <div class="modal fade" id="userModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Kullanıcı Bilgileri</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="userForm">
                        @csrf
                        <input type="hidden" id="user_id">
                        <input type="hidden" name="_method" id="_method">
                        <div class="alert alert-danger d-none" id="errorMessages"></div> <!-- HATA GÖSTERİM ALANI -->

                        <div class="mb-3">
                            <label>Kullanıcı Adı</label>
                            <input type="text" class="form-control" name="username" id="username" required>
                        </div>
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" class="form-control" name="email" id="email" required>
                        </div>
                        <div class="mb-3">
                            <label>Şifre</label>
                            <input type="password" class="form-control" name="password" id="password">
                        </div>
                        <div class="mb-3">
                            <label>Rol</label>
                            <select id="is_Admin" name="is_Admin" class="form-control">
                                <option value="0">Kullanıcı</option>
                                <option value="1">Admin</option>
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
            let table = $('#usersTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.users.data') }}',
                language: {
                    url: "{{ asset('assets/datatables/turkish.json') }}"
                },
                columns: [
                    {data: 'id'},
                    {data: 'username'},
                    {data: 'email'},
                    {data: 'role', orderable: false, searchable: false},
                    {data: 'actions', orderable: false, searchable: false}
                ]
            });

            $('#addUserBtn').click(function () {
                $('#userForm')[0].reset();
                $('#user_id').val('');
                $('#_method').val('POST');
                $('#errorMessages').html('').addClass('d-none');
                $('#userModal').modal('show');
            });

            $(document).on('click', '.edit-user', function () {
                $('#user_id').val($(this).data('id'));
                $('#username').val($(this).data('username'));
                $('#email').val($(this).data('email'));
                $('#is_Admin').val($(this).data('role'));
                $('#_method').val('PUT');
                $('#errorMessages').html('').addClass('d-none');
                $('#userModal').modal('show');
            });

            $(document).on('click', '.delete-user', function () {
                let userId = $(this).data('id');

                Swal.fire({
                    title: 'Emin misiniz?',
                    text: "Bu kullanıcıyı silmek istediğinize emin misiniz?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Evet, sil!',
                    cancelButtonText: 'Hayır'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/admin/users/delete/${userId}`,
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

            $('#userForm').submit(function (e) {
                e.preventDefault();
                let userId = $('#user_id').val();
                let url = userId ? `/admin/users/update/${userId}` : '/admin/users/store';
                let method = userId ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    method: method,
                    data: {
                        _token: "{{ csrf_token() }}",
                        _method: method,
                        username: $('#username').val(),
                        email: $('#email').val(),
                        password: $('#password').val(),
                        is_Admin: $('#is_Admin').val()
                    },
                    success: function (response) {
                        table.ajax.reload();
                        $('#userModal').modal('hide');
                        Swal.fire('Başarılı', response.message, 'success');
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let errorMessages = '<ul>';
                            $.each(errors, function (key, value) {
                                errorMessages += '<li>' + value + '</li>';
                            });
                            errorMessages += '</ul>';
                            $('#errorMessages').html(errorMessages).removeClass('d-none'); // Hataları göster
                        } else {
                            Swal.fire('Hata', 'Bir hata oluştu, lütfen tekrar deneyin.', 'error');
                        }
                    }
                });
            });
        });
    </script>
@endsection
