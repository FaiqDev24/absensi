@extends('templates.app')

@section('content')
    <div class="container mt-5">
        @if (Session::get('success'))
            <div class="alert alert-success">{{ Session::get('success') }} <b>Selamat Datang, {{ Auth::user()->name }}</b>
            </div>
        @endif
    </div>
    <div class="row justify-content-center m-3">
        <!-- Box Jumlah Siswa -->
        <div class="col-md-4 mb-3">
            <div class="card text-center p-3 shadow-lg border-0 rounded-4">
                <div class="card-body">
                    <div class="bg-danger text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                        style="width:70px; height:70px;">
                        <i class="bi bi-people fs-2"></i>
                    </div>
                    <h5>Jumlah Guru</h5>
                    <h2>{{ $teacherCount }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-center p-3 shadow-lg border-0 rounded-4">
                <div class="card-body">
                    <div class="bg-success text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                        style="width:70px; height:70px;">
                        <i class="bi bi-building fs-2"></i>
                    </div>
                    <h5>Jumlah Kelas</h5>
                    <h2>{{ $classCount }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-center p-3 shadow-lg border-0 rounded-4">
                <div class="card-body">
                    <div class="bg-primary text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                        style="width:70px; height:70px;">
                        <i class="bi bi-people fs-2"></i>
                    </div>
                    <h5>Jumlah Siswa</h5>
                    <h2>{{ $studentCount }}</h2>
                </div>
            </div>
        </div>
    </div>
@endsection
