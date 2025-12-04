@extends('templates.app')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Edit Profile</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Preview Foto Profil -->
                            <div class="mb-3 text-center">
                                @if ($user->profile_photo)
                                    <img id="preview" src="{{ asset('storage/' . $user->profile_photo) }}"
                                        alt="Profile Photo" class="rounded-circle"
                                        style="width: 120px; height: 120px; object-fit: cover;">
                                @else
                                    <div id="preview"
                                        class="rounded-circle bg-secondary d-flex align-items-center justify-content-center mx-auto"
                                        style="width: 120px; height: 120px;">
                                        <i class="fas fa-user fa-2x text-white"></i>
                                    </div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Foto Profil</label>
                                <input type="file" name="profile_photo"
                                    class="form-control @error('profile_photo') is-invalid @enderror" accept="image/*"
                                    onchange="previewImage(event)">
                                <small class="text-muted">Maksimal ukuran: 2MB. Format: JPEG, PNG, JPG, GIF</small>
                                @error('profile_photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nama</label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $user->name) }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $user->email) }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                <a href="{{ route('profile.show') }}" class="btn btn-secondary">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            const reader = new FileReader();

            reader.onload = function() {
                const preview = document.getElementById('preview');
                preview.innerHTML = '<img src="' + reader.result +
                    '" class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover;">';
            };

            if (file) {
                reader.readAsDataURL(file);
            }
        }
    </script>
@endsection
