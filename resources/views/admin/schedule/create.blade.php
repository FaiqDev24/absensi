@extends('templates.app')

@section('content')
    <div class="container mt-5">
        <h5 class="mb-3">Tambah Jadwal Mengajar</h5>

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('admin.schedules.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="teacher_id" class="form-label">Guru</label>
                <select name="teacher_id" id="teacher_id" class="form-select @error('teacher_id') is-invalid @enderror"
                    required>
                    <option value="">-- Pilih Guru --</option>
                    @foreach ($teachers as $teacher)
                        <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                            {{ $teacher->user->name }}
                        </option>
                    @endforeach
                </select>
                @error('teacher_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="subject_id" class="form-label">Mata Pelajaran</label>
                <select name="subject_id" id="subject_id" class="form-select @error('subject_id') is-invalid @enderror"
                    required>
                    <option value="">-- Pilih Mata Pelajaran --</option>
                    @foreach ($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
                @error('subject_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="class_room_id" class="form-label">Kelas</label>
                <select name="class_room_id" id="class_room_id"
                    class="form-select @error('class_room_id') is-invalid @enderror" required>
                    <option value="">-- Pilih Kelas --</option>
                    @foreach ($classRooms as $class)
                        <option value="{{ $class->id }}" {{ old('class_room_id') == $class->id ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
                @error('class_room_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="date" class="form-label">Tanggal</label>
                <input type="date" name="date" id="date" class="form-control @error('date') is-invalid @enderror"
                    value="{{ old('date') }}" required>
                @error('date')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="start_time" class="form-label">Jam Mulai</label>
                    <input type="time" name="start_time" id="start_time"
                        class="form-control @error('start_time') is-invalid @enderror" value="{{ old('start_time') }}"
                        required>
                    @error('start_time')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="end_time" class="form-label">Jam Selesai</label>
                    <input type="time" name="end_time" id="end_time"
                        class="form-control @error('end_time') is-invalid @enderror" value="{{ old('end_time') }}"
                        required>
                    @error('end_time')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> <strong>Catatan:</strong> Sistem akan mencegah konflik jadwal (guru/kelas
                tidak bisa ada di 2 tempat di waktu yang sama)
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.schedules.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#teacher_id, #subject_id, #class_room_id').select2({
                placeholder: function() {
                    return $(this).data('placeholder');
                },
                allowClear: true,
                width: '100%'
            });
        });
    </script>
@endsection
@endsection
