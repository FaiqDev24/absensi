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

                            <!-- Preview Foto Profil (Click-to-Change) -->
                            <div class="mb-3 text-center" style="cursor: pointer;"
                                 onclick="document.getElementById('profileInput').click();">

                                @if ($user->profile_photo)
                                    <img id="preview" src="{{ asset('storage/' . $user->profile_photo) }}"
                                        alt="Profile Photo" class="rounded-circle"
                                        style="width: 120px; height: 120px; object-fit: cover;">
                                @else
                                    <img id="preview"
                                        src="https://via.placeholder.com/120?text=User"
                                        class="rounded-circle"
                                        style="width: 120px; height: 120px; object-fit: cover;">
                                @endif
                            </div>

                            <!-- Hidden File Input -->
                            <input type="file" id="profileInput" name="profile_photo"
                                class="d-none @error('profile_photo') is-invalid @enderror"
                                accept="image/*"
                                onchange="previewImage(event)">

                            @error('profile_photo')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror

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
                                <label class="form-label">Password Lama</label>
                                <input type="password" name="old_password"
                                    class="form-control @error('old_password') is-invalid @enderror"
                                    placeholder="Masukkan password lama untuk mengubah password">
                                @error('old_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password Baru</label>
                                <input type="password" name="new_password"
                                    class="form-control @error('new_password') is-invalid @enderror"
                                    placeholder="Kosongkan jika tidak ingin mengubah password">
                                @error('new_password')
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
            const preview = document.getElementById('preview');

            if (file) {
                preview.src = URL.createObjectURL(file); // Update preview instantly
            }
        }
    </script>
@endsection
