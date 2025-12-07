@extends('templates.app')

@section('content')
    <div class="container mt-5">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h5>Profile Saya</h5>
                    </div>
                    <div class="card-body">
                        <!-- Foto Profil -->
                        <div class="mb-3 text-center">
                            @if ($user->profile_photo)
                                <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Profile Photo"
                                    class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center mx-auto"
                                    style="width: 150px; height: 150px;">
                                    <i class="fas fa-user fa-3x text-white"></i>
                                </div>
                            @endif
                        </div>

                        <table class="table table-borderless">
                            <tr>
                                <th style="width:150px">Nama</th>
                                <td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th>Role</th>
                                <td>
                                    @if ($user->role == 'admin')
                                        <span class="badge bg-primary">Admin</span>
                                    @elseif($user->role == 'teacher')
                                        <span class="badge bg-success">Guru</span>
                                    @else
                                        <span class="badge bg-warning">Siswa</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Terdaftar Pada</th>
                                <td>{{ \Carbon\Carbon::parse($user->created_at)->format('d M Y') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary">Edit Profile</a>
                        @if ($user->role == 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Kembali</a>
                        @elseif($user->role == 'teacher')
                            <a href="{{ route('teacher.dashboard') }}" class="btn btn-secondary">Kembali</a>
                        @else
                            <a href="{{ route('student.dashboard') }}" class="btn btn-secondary">Kembali</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
