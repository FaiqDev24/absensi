@extends('templates.app')

@section('content')
    <div class="container mt-5">
        @if (Session::get('success'))
            <div class="alert alert-success">{{ Session::get('success') }} <b>Selamat Datang, {{ Auth::user()->name }}</b>
            </div>
        @endif

        <div class="card shadow-sm mt-4">
            <div class="card-header bg-primary text-white">
                Informasi Siswa
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <img src="{{ Auth::user()->profile_photo ? asset('storage/' . Auth::user()->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}"
                            alt="Profile Photo" class="img-fluid rounded border">
                    </div>
                    <div class="col-md-8">
                        <table class="table table-borderless">
                            <tr>
                                <th width="30%">Nama</th>
                                <td>: {{ Auth::user()->name }}</td>
                            </tr>
                            <tr>
                                <th>NIS</th>
                                <td>: {{ $student->nis ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Kelas</th>
                                <td>: {{ $student->classRoom->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Jenis Kelamin</th>
                                <td>: {{ $student->gender ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Lahir</th>
                                <td>: {{ $student->birth_date ? $student->birth_date->format('d F Y') : '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mt-4">
            <div class="card-header bg-secondary text-white">
                Statistik Kehadiran
            </div>
            <div class="card-body text-center">
                <h3>{{ $student->total_attendance ?? 0 }}</h3>
                <p class="text-muted mb-0">Total Kehadiran</p>
            </div>
        </div>
    </div>
@endsection
