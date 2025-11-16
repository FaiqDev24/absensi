@extends('templates.app')

@section('content')
    <div class="container mt-5">
        @if (Session::get('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
        @endif
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5>Data Guru Terhapus</h5>
            <a href="{{ route('admin.classrooms.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Kelas</th>
                    <th>Dihapus Pada</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($classRooms as $index => $item)
                    <tr>
                        {{-- index dari 0, biar muncul xdari 1 -> +1 --}}
                        <th>{{ $index + 1 }}</th>
                        {{-- name, location dari fillable model cinema --}}
                        <th>{{ $item['name'] }}</th>
                        <td>{{ $item->deleted_at->format('d/m/Y H:i') }}</td>
                        <td class="d-flex gap-1">
                            <form action="{{ route('admin.classrooms.restore', ['id' => $item->id]) }}" method="POST">
                                @csrf
                                <button class="btn btn-success btn-sm" onclick="return confirm('Pulihkan data ini?')">
                                    Pulihkan
                                </button>
                            </form>
                            <form action="{{ route('admin.classrooms.force-delete', ['id' => $item->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus permanen data ini?')">
                                    Hapus Permanen
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">Tidak ada data terhapus.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
