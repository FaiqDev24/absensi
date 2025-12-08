@extends('templates.app')

@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Jadwal Mengajar Saya</h5>
            <a href="{{ route('teacher.schedules.export-pdf', request()->all()) }}" class="btn btn-danger">     
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
        </div>

        <!-- Filter -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('teacher.schedules.index') }}">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Hari</label>
                            <select name="day" class="form-select">
                                <option value="">Semua Hari</option>
                                @foreach ($days as $d)
                                    <option value="{{ $d }}" {{ request('day') == $d ? 'selected' : '' }}>
                                        {{ $d }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
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
                        <div class="col-md-4">
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
                        <th style="width:60px">No</th>
                        <th>Hari</th>
                        <th>Waktu</th>
                        <th>Mata Pelajaran</th>
                        <th>Kelas</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($schedules as $i => $schedule)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $schedule->day }}</td>
                            <td>{{ date('H:i', strtotime($schedule->start_time)) }} -
                                {{ date('H:i', strtotime($schedule->end_time)) }}</td>
                            <td>{{ $schedule->subject->name ?? '-' }}</td>
                            <td>{{ $schedule->classRoom->name ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada jadwal mengajar</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
