@extends('templates.app')

@section('content')
    <div class="content-body">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <!-- Session/Error Messages -->
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="card shadow-sm rounded-4 mt-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold">Ubah Password</h5>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('profile.update-password') }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label for="old_password" class="form-label">Password Lama</label>
                                    <input type="password" class="form-control @error('old_password') is-invalid @enderror"
                                        id="old_password" name="old_password" placeholder="Masukkan password lama" required>
                                    @error('old_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="new_password" class="form-label">Password Baru</label>
                                    <input type="password" class="form-control @error('new_password') is-invalid @enderror"
                                        id="new_password" name="new_password"
                                        placeholder="Masukkan password baru (min 8 karakter)" required>
                                    @error('new_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="new_password_confirmation" class="form-label">Konfirmasi Password
                                        Baru</label>
                                    <input type="password" class="form-control" id="new_password_confirmation"
                                        name="new_password_confirmation" placeholder="Ulangi password baru" required>
                                </div>

                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('profile.show') }}" class="btn btn-light">Batal</a>
                                    <button type="submit" class="btn btn-primary">Simpan Password</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
