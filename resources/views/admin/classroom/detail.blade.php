@extends('templates.app')

@section('content')
    <div class="container mt-5">
        <h5 class="text-center mb-3">Detai Kelas</h5>
        <div class="mb-3">
            <a href="{{ route('admin.classrooms.index') }}" class="btn btn-secondary ">Kembali</a>

        </div>
        <table class="table table-bordered">
            <tr style="font-size:20px">
                <th>Nama Kelas</th>
                <td><strong>{{ $classRoom->name }}</strong></td>
            </tr>
            <tr style="font-size:20px">
                <th>Jumlah Murid</th>
                <td>{{ $classRoom->students->count() }}</td>
            </tr>
        </table>
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <h5 class="text-center mb-3 mt-5">Daftar Murid</h5>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th style="width:60px">No Absen</th>
                        <th>NIS</th>
                        <th>Nama</th>
                        <th>Gender</th>
                        <th style="width:160px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($classRoom->students as $i => $student)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $student->nis ?? '-' }}</td>
                            <td>{{ $student->user->name }}</td>
                            <td>{{ $student->gender }}</td>
                            <td class="d-flex">
                                <form action="{{ route('admin.students.destroy', ['id' => $student->id]) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="detach" value="1">
                                    <button class="btn btn-sm btn-danger">Keluarkan</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada murid pada kelas ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
