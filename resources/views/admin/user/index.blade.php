@extends('templates.app')

@section('content')
    <div class="container mt-5">
        @if (Session::get('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
        @endif
        <div class="justify-content-end d-flex">
            <a href="{{ route('admin.users.trash') }}" class="btn btn-warning me-2">Data Sampah</a>
            {{-- <a href="{{ route('admin.users.export') }}" class="btn btn-secondary me-2">Export (.xlsx)</a>
            <a href="{{ route('admin.users.create') }}" class="btn btn-success">Tambah Data</a> --}}
        </div>
        <h5 class="mt-3">Data Siswa</h5>
        <table class="table table-bordered">
            <tr>
                <th>#</th>
                <th>Email</th>
                <th>Nama</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
            @foreach ($users as $index => $item)
                <tr>
                    {{-- index dari 0, biar muncul xdari 1 -> +1 --}}
                    <th>{{ $index + 1 }}</th>
                    {{-- name, location dari fillable model cinema --}}
                    <th>{{ $item['email'] }}</th>
                    <th>{{ $item['name'] }}</th>
                    <th>
                        @if ($item['role'] == 'admin')
                            <span class="badge badge-primary">Admin</span>
                        @elseif ($item['role'] == 'teacher')
                            <span class="badge badge-success">Teacher</span>
                        @else
                            <span class="badge badge-warning">Student</span>
                        @endif
                    </th>
                    <th class="d-flex">
                        {{-- ['id' => $item['id']] untuk mengirim id ke route --}}
                        <a href="{{ route('admin.users.edit', ['id' => $item['id']]) }}"
                            class="btn btn-secondary m-2">Edit</a>
                        <form action="{{ route('admin.users.destroy', ['id' => $item['id']]) }}" method="POST">
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
