@extends('templates.app')

@section('content')
    <div class="container mt-5">
        <h5 class="text-center mb-3">Detail Kelas {{ $classRoom->name }}</h5>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <table class="table table-bordered mb-4">
            <tr style="font-size:20px">
                <th>Nama Kelas</th>
                <td><strong>{{ $classRoom->name }}</strong></td>
            </tr>
            <tr style="font-size:20px">
                <th>Jumlah Murid</th>
                <td>{{ $classRoom->students->count() }}</td>
            </tr>
        </table>

        <!-- Form Input Kehadiran -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0">Input/Edit Kehadiran Siswa</h6>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('teacher.classrooms.show', $classRoom->id) }}">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label>Jadwal Mengajar</label>
                            <select name="schedule_id" id="schedule_id" class="form-select" required>
                                <option value="">Pilih Jadwal</option>
                                @forelse($teacherSchedules as $schedule)
                                    <option value="{{ $schedule->id }}"
                                        {{ request('schedule_id') == $schedule->id ? 'selected' : '' }}
                                        data-subject="{{ $schedule->subject_id }}">
                                        {{ \Carbon\Carbon::parse($schedule->date)->translatedFormat('l, d M Y') }} |
                                        {{ date('H:i', strtotime($schedule->start_time)) }}-{{ date('H:i', strtotime($schedule->end_time)) }}
                                        | {{ $schedule->subject->name }}
                                    </option>
                                @empty
                                    <option value="" disabled>Anda belum memiliki jadwal mengajar di kelas ini
                                    </option>
                                @endforelse
                            </select>
                            @if ($teacherSchedules->isEmpty())
                                <small class="text-muted">Silakan hubungi admin untuk mengatur jadwal mengajar Anda</small>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <label>Tanggal</label>
                            <input type="date" name="date" class="form-control"
                                value="{{ request('date', date('Y-m-d')) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary w-100">Tampilkan/Edit Kehadiran</button>
                        </div>
                    </div>
                </form>

                @if (request('schedule_id') && request('date'))
                    @php
                        $selectedSchedule = \App\Models\Schedule::find(request('schedule_id'));
                    @endphp

                    @if ($selectedSchedule)
                        <div class="alert alert-info">
                            <strong>Jadwal Terpilih:</strong>
                            {{ \Carbon\Carbon::parse($selectedSchedule->date)->translatedFormat('l, d M Y') }} |
                            {{ date('H:i', strtotime($selectedSchedule->start_time)) }}-{{ date('H:i', strtotime($selectedSchedule->end_time)) }}
                            |
                            {{ $selectedSchedule->subject->name }}
                        </div>

                        <hr>
                        <form method="POST" action="{{ route('teacher.attendance.bulk-store') }}">
                            @csrf
                            <input type="hidden" name="class_room_id" value="{{ $classRoom->id }}">
                            <input type="hidden" name="subject_id" value="{{ $selectedSchedule->subject_id }}">
                            <input type="hidden" name="date" value="{{ request('date') }}">

                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width:50px">No</th>
                                            <th>NIS</th>
                                            <th>Nama Siswa</th>
                                            <th style="width:80px" class="text-center">Hadir</th>
                                            <th style="width:80px" class="text-center">Sakit</th>
                                            <th style="width:80px" class="text-center">Izin</th>
                                            <th style="width:80px" class="text-center">Alpha</th>
                                            <th>Catatan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $existingAttendances = \App\Models\Attendance::where(
                                                'class_room_id',
                                                $classRoom->id,
                                            )
                                                ->where('subject_id', $selectedSchedule->subject_id)
                                                ->whereDate('date', request('date'))
                                                ->get()
                                                ->keyBy('student_id');
                                        @endphp
                                        @foreach ($classRoom->students as $i => $student)
                                            @php
                                                $attendance = $existingAttendances->get($student->id);
                                                $currentStatus = $attendance->status ?? 'hadir'; // default value hadir
                                                $currentNotes = $attendance ? $attendance->notes : '';
                                            @endphp
                                            <tr>
                                                <td>{{ $i + 1 }}</td>
                                                <td>{{ $student->nis ?? '-' }}</td>
                                                <td>{{ $student->user->name }}</td>
                                                <input type="hidden" name="attendances[{{ $i }}][student_id]"
                                                    value="{{ $student->id }}">
                                                <td class="text-center">
                                                    <input type="radio" name="attendances[{{ $i }}][status]"
                                                        value="hadir" {{ $currentStatus == 'hadir' ? 'checked' : '' }}
                                                        required>
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="attendances[{{ $i }}][status]"
                                                        value="sakit" {{ $currentStatus == 'sakit' ? 'checked' : '' }}
                                                        required>
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="attendances[{{ $i }}][status]"
                                                        value="izin" {{ $currentStatus == 'izin' ? 'checked' : '' }}
                                                        required>
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="attendances[{{ $i }}][status]"
                                                        value="alpha" {{ $currentStatus == 'alpha' ? 'checked' : '' }}
                                                        required>
                                                </td>
                                                <td>
                                                    <input type="text" name="attendances[{{ $i }}][notes]"
                                                        class="form-control form-control-sm" placeholder="Catatan"
                                                        value="{{ $currentNotes }}">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="text-end mt-3">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Simpan Kehadiran
                                </button>
                            </div>
                        </form>
                    @endif
                @endif
            </div>
        </div>

        <!-- Daftar Murid -->
        <h5 class="text-center mb-3 mt-5">Daftar Murid</h5>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th style="width:60px">No Absen</th>
                        <th>NIS</th>
                        <th>Nama</th>
                        <th>Gender</th>
                        <th>Total Kehadiran</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($classRoom->students as $i => $student)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $student->nis ?? '-' }}</td>
                            <td>{{ $student->user->name }}</td>
                            <td>{{ $student->gender }}</td>
                            <td>{{ $student->attendances()->where('status', 'hadir')->count() }} kali hadir</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada murid pada kelas ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
