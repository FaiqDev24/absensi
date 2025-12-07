@extends('templates.app')

@section('content')
    <div class="container mt-5">
        @if (Session::get('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
        @endif
        <div class="justify-content-end d-flex">
            <a href="{{ route('admin.students.trash') }}" class="btn btn-warning me-2">Data Sampah</a>
            <a href="{{ route('admin.students.export') }}" class="btn btn-secondary me-2">Export (.xlsx)</a>
            <a href="{{ route('admin.students.create') }}" class="btn btn-success">Tambah Data</a>
        </div>
        <h5 class="mt-3">Data Siswa</h5>
        <table class="table table-bordered">
            <tr>
                <th>#</th>
                <th>NIS</th>
                <th>Nama Siswa</th>
                <th>Gender</th>
                <th>Kelas</th>
                <th>Aksi</th>
            </tr>
            @foreach ($students as $index => $item)
                <tr>
                    {{-- index dari 0, biar muncul xdari 1 -> +1 --}}
                    <th>{{ $index + 1 }}</th>
                    {{-- name, location dari fillable model cinema --}}
                    <th>{{ $item['nis'] }}</th>
                    <th>{{ $item->user->name ?? $item['name'] }}</th>
                    <th>{{ $item['gender'] }}</th>
                    <th>{{ $item->classRoom->name ?? '-' }}</th>
                    <th class="d-flex">
                        {{-- ['id' => $item['id']] untuk mengirim id ke route --}}
                        <a href="{{ route('admin.students.edit', ['id' => $item['id']]) }}"
                            class="btn btn-secondary m-2">Edit</a>
                        <form action="{{ route('admin.students.destroy', ['id' => $item['id']]) }}" method="POST">
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
