@extends('templates.app')

@section('content')
    <div class="container mt-5">
        <h5 class="mb-3">Jadwal Mengajar</h5>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Filter -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.schedules.index') }}">
                    <div class="row">
                        <div class="col-md-2">
                            <label>Hari</label>
                            <select name="day" class="form-select">
                                <option value="">Semua Hari</option>
                                @foreach ($days as $d)
                                    <option value="{{ $d }}" {{ request('day') == $d ? 'selected' : '' }}>
                                        {{ $d }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Guru</label>
                            <select name="teacher_id" class="form-select">
                                <option value="">Semua Guru</option>
                                @foreach ($teachers as $t)
                                    <option value="{{ $t->id }}"
                                        {{ request('teacher_id') == $t->id ? 'selected' : '' }}>
                                        {{ $t->user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>Kelas</label>
                            <select name="class_room_id" class="form-select">
                                <option value="">Semua Kelas</option>
                                @foreach ($classRooms as $c)
                                    <option value="{{ $c->id }}"
                                        {{ request('class_room_id') == $c->id ? 'selected' : '' }}>
                                        {{ $c->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Mata Pelajaran</label>
                            <select name="subject_id" class="form-select">
                                <option value="">Semua Mapel</option>
                                @foreach ($subjects as $s)
                                    <option value="{{ $s->id }}"
                                        {{ request('subject_id') == $s->id ? 'selected' : '' }}>
                                        {{ $s->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="mb-3">
            <a href="{{ route('admin.schedules.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Tambah Jadwal
            </a>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th style="width:60px">No</th>
                        <th>Hari</th>
                        <th>Waktu</th>
                        <th>Guru</th>
                        <th>Mata Pelajaran</th>
                        <th>Kelas</th>
                        <th style="width:120px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($schedules as $i => $schedule)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $schedule->day }}</td>
                            <td>{{ date('H:i', strtotime($schedule->start_time)) }} -
                                {{ date('H:i', strtotime($schedule->end_time)) }}</td>
                            <td>{{ $schedule->teacher->user->name }}</td>
                            <td>{{ $schedule->subject->name }}</td>
                            <td>{{ $schedule->classRoom->name }}</td>
                            <td>
                                <a href="{{ route('admin.schedules.edit', $schedule->id) }}"
                                    class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.schedules.destroy', $schedule->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Yakin hapus jadwal ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada jadwal</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
