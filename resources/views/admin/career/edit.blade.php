@extends('admin.layouts.app')

@section('content')
    <div class="alert alert-primary mt-2">
        <h5>Kariyer Sayfası Yönetimi Hakkında</h5>
        <p>Bu panel, web sitesinin kariyer sayfasındaki içeriğin güncellenmesi için kullanılır. Adminler, kariyer fırsatları hakkında bilgilendirme metni ekleyebilir ve sayfa için bir görsel yükleyebilir.</p>

        <h6>Girdi Alanları:</h6>
        <ul>
            <li><strong>İçerik:</strong> Kariyer sayfasında görüntülenecek açıklama metni.</li>
            <li><strong>Görsel:</strong> Opsiyonel bir alan olup, sayfaya bir görsel eklemeyi sağlar.</li>
        </ul>
    </div>

    <div class="card" >
        <div class="card-header bg-primary text-white d-flex justify-content-between"  style="background: linear-gradient(135deg, #1e3c72, #2a5298); color: white; border-radius: 10px 10px 0 0;">
            <span class="fw-bold fs-5">Kariyer Sayfası</span>
        </div>
        <div class="card-body">
            <form id="careerForm" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label>İçerik</label>
                    <textarea class="form-control" name="content" id="content" required>{{ $career->content ?? '' }}</textarea>
                </div>
                <div class="mb-3">
                    <label>Görsel</label>
                    <input type="file" class="form-control" name="image" id="image">
                    @if (!empty($career->image))
                        <img src="{{ asset('uploads/career/'.$career->image) }}" id="previewImage" class="mt-2" width="100" height="100">
                    @else
                        <img id="previewImage" class="mt-2" width="100" height="100" style="display:none;">
                    @endif
                </div>
                <button type="submit" class="btn btn-primary w-100">Kaydet</button>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            $('#careerForm').submit(function (e) {
                e.preventDefault();
                let formData = new FormData(this);

                $.ajax({
                    url: '{{ route('admin.career.update') }}',
                    method: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        Swal.fire('Başarılı', response.message, 'success');
                    },
                    error: function () {
                        Swal.fire('Hata', 'Bir hata oluştu, lütfen tekrar deneyin.', 'error');
                    }
                });
            });

            $('#image').change(function (event) {
                let reader = new FileReader();
                reader.onload = function (e) {
                    $('#previewImage').attr('src', e.target.result).show();
                };
                reader.readAsDataURL(event.target.files[0]);
            });
        });
    </script>
@endsection
