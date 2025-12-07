@extends('templates.app')

@section('content')
    <div class="container mt-5">
        <h5 class="text-center mb-3">Daftar Kehadiran</h5>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-3">
            <a href="{{ route('teacher.attendance.create') }}" class="btn btn-primary">Input Kehadiran</a>
            <a href="{{ route('teacher.attendance.report') }}" class="btn btn-info">Laporan Kehadiran</a>
        </div>

        <!-- Filter -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('teacher.attendance.index') }}">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Tanggal</label>
                            <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                        </div>
                        <div class="col-md-3">
                            <label>Kelas</label>
                            <select name="class_room_id" class="form-select">
                                <option value="">Semua Kelas</option>
                                @foreach ($classRooms as $class)
                                    <option value="{{ $class->id }}"
                                        {{ request('class_room_id') == $class->id ? 'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Mata Pelajaran</label>
                            <select name="subject_id" class="form-select">
                                <option value="">Semua Mata Pelajaran</option>
                                @foreach ($subjects as $subj)
                                    <option value="{{ $subj->id }}"
                                        {{ request('subject_id') == $subj->id ? 'selected' : '' }}>
                                        {{ $subj->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Siswa</th>
                        <th>Kelas</th>
                        <th>Mata Pelajaran</th>
                        <th>Status</th>
                        <th>Catatan</th>
                        <th style="width:120px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $i => $attendance)
                        <tr>
                            <td>{{ $attendances->firstItem() + $i }}</td>
                            <td>{{ $attendance->date->format('d/m/Y') }}</td>
                            <td>{{ $attendance->student->name }}</td>
                            <td>{{ $attendance->classRoom->name ?? '-' }}</td>
                            <td>{{ $attendance->subject->name ?? '-' }}</td>
                            <td>
                                <span
                                    class="badge bg-{{ $attendance->status == 'hadir' ? 'success' : ($attendance->status == 'sakit' ? 'warning' : ($attendance->status == 'izin' ? 'info' : 'danger')) }}">
                                    {{ ucfirst($attendance->status) }}
                                </span>
                            </td>
                            <td>{{ $attendance->notes ?? '-' }}</td>
                            <td>
                                <a href="{{ route('teacher.attendance.edit', $attendance->id) }}"
                                    class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('teacher.attendance.destroy', $attendance->id) }}" method="POST"
                                    class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Belum ada data kehadiran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $attendances->links() }}
        </div>
    </div>
@endsection
