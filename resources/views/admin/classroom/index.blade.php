@extends('templates.app')

@section('content')
    <div class="container mt-5">
        @if (Session::get('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ Session::get('success') }}
                <button type="button" class="btn-close" data-mdb-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (Session::get('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ Session::get('error') }}
                <button type="button" class="btn-close" data-mdb-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="justify-content-end d-flex">
            <a href="{{ route('admin.classrooms.trash') }}" class="btn btn-warning me-2">Data Sampah</a>
            <a href="{{ route('admin.classrooms.create') }}" class="btn btn-success">+ Tambah Data</a>
        </div>
        <h5 class="mt-3">Data Siswa</h5>
        <table class="table table-bordered">
            <tr>
                <th>#</th>
                <th>Kelas</th>
                <th>Aksi</th>
            </tr>
            @foreach ($classRooms as $index => $item)
                <tr>
                    {{-- index dari 0, biar muncul xdari 1 -> +1 --}}
                    <th>{{ $index + 1 }}</th>
                    {{-- name, location dari fillable model cinema --}}
                    <th>{{ $item['name'] }}</th>
                    <th class="d-flex">
                        {{-- ['id' => $item['id']] untuk mengirim id ke route --}}
                        <a href="{{ route('admin.classrooms.show', ['id' => $item['id']]) }}"
                            class="btn btn-info m-2">Detail Kelas</a>
                        <a href="{{ route('admin.classrooms.edit', ['id' => $item['id']]) }}"
                            class="btn btn-secondary m-2">Edit</a>
                        <form action="{{ route('admin.classrooms.destroy', ['id' => $item['id']]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger m-2">Hapus</button>
                        </form>
                    </th>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
