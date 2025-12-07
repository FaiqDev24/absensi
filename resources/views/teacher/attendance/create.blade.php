@extends('templates.app')

@section('content')
    <div class="container mt-5">
        <h5 class="text-center mb-3">Input Kehadiran Siswa</h5>

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

        <!-- Form Filter -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('teacher.attendance.create') }}">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Kelas</label>
                            <select name="class_room_id" class="form-select" required>
                                <option value="">Pilih Kelas</option>
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
                            <select name="subject_id" class="form-select" required>
                                <option value="">Pilih Mata Pelajaran</option>
                                @php
                                    $teacher = \App\Models\Teacher::where('id_user', Auth::id())->first();
                                    $teacherSubjects = $teacher ? $teacher->subjects : collect();
                                @endphp
                                @forelse($teacherSubjects as $subj)
                                    <option value="{{ $subj->id }}"
                                        {{ request('subject_id') == $subj->id ? 'selected' : '' }}>
                                        {{ $subj->name }}
                                    </option>
                                @empty
                                    <option value="" disabled>Anda belum ditugaskan mata pelajaran</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Tanggal</label>
                            <input type="date" name="date" class="form-control"
                                value="{{ request('date', date('Y-m-d')) }}" required>
                        </div>
                        <div class="col-md-3">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary w-100">Tampilkan Siswa</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if ($students && $students->count() > 0)
            <!-- Form Input Kehadiran -->
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('teacher.attendance.bulk-store') }}">
                        @csrf
                        <input type="hidden" name="class_room_id" value="{{ request('class_room_id') }}">
                        <input type="hidden" name="subject_id" value="{{ request('subject_id') }}">
                        <input type="hidden" name="date" value="{{ request('date', date('Y-m-d')) }}">

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width:60px">No</th>
                                        <th>NIS</th>
                                        <th>Nama Siswa</th>
                                        <th style="width:100px">Hadir</th>
                                        <th style="width:100px">Sakit</th>
                                        <th style="width:100px">Izin</th>
                                        <th style="width:100px">Alpha</th>
                                        <th>Catatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($students as $i => $student)
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td>{{ $student->nis ?? '-' }}</td>
                                            <td>{{ $student->name }}</td>
                                            <input type="hidden" name="attendances[{{ $i }}][student_id]"
                                                value="{{ $student->id }}">
                                            <td class="text-center">
                                                <input type="radio" name="attendances[{{ $i }}][status]"
                                                    value="hadir"
                                                    {{ !in_array($student->id, $existingAttendances ?? []) ? 'checked' : '' }}
                                                    required>
                                            </td>
                                            <td class="text-center">
                                                <input type="radio" name="attendances[{{ $i }}][status]"
                                                    value="sakit" required>
                                            </td>
                                            <td class="text-center">
                                                <input type="radio" name="attendances[{{ $i }}][status]"
                                                    value="izin" required>
                                            </td>
                                            <td class="text-center">
                                                <input type="radio" name="attendances[{{ $i }}][status]"
                                                    value="alpha" required>
                                            </td>
                                            <td>
                                                <input type="text" name="attendances[{{ $i }}][notes]"
                                                    class="form-control form-control-sm" placeholder="Catatan (optional)">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between mt-3">
                            <a href="{{ route('teacher.attendance.index') }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">Simpan Kehadiran</button>
                        </div>
                    </form>
                </div>
            </div>
        @elseif(request('class_room_id') && request('subject_id'))
            <div class="alert alert-info">
                Tidak ada siswa di kelas ini.
            </div>
        @endif
    </div>
@endsection
