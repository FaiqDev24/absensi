@extends('templates.app')

@section('content')
    <div class="container mt-5">
        @if (Session::get('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
        @endif

        <div class="justify-content-end d-flex mb-3">
            <a href="{{ route('admin.subjects.create') }}" class="btn btn-success">Tambah Mata Pelajaran</a>
        </div>

        <h5>Data Mata Pelajaran</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Mata Pelajaran</th>
                    <th>Guru Pengajar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($subjects as $subject)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $subject->name }}</td>
                        <td>
                            @foreach ($subject->teachers as $teacher)
                                <span>{{ $teacher->name }}@if(!$loop->last), @endif</span>
                            @endforeach
                        </td>
                        <td class="d-flex">
                            <a href="{{ route('admin.subjects.edit', ['id' => $subject->id]) }}"
                                class="btn btn-secondary btn-sm me-2">Edit</a>
                            <form action="{{ route('admin.subjects.destroy', ['id' => $subject->id]) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">Belum ada data mata pelajaran</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
