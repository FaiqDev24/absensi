@extends('templates.app')

@section('content')
    <div class="container mt-5">
        <h5 class="mb-3">Edit Jadwal Mengajar</h5>

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('admin.schedules.update', $schedule->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="teacher_id" class="form-label">Guru</label>
                <select name="teacher_id" id="teacher_id" class="form-select @error('teacher_id') is-invalid @enderror"
                    required>
                    <option value="">-- Pilih Guru --</option>
                    @foreach ($teachers as $teacher)
                        <option value="{{ $teacher->id }}"
                            {{ old('teacher_id', $schedule->teacher_id) == $teacher->id ? 'selected' : '' }}>
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
                        <option value="{{ $subject->id }}"
                            {{ old('subject_id', $schedule->subject_id) == $subject->id ? 'selected' : '' }}>
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
                        <option value="{{ $class->id }}"
                            {{ old('class_room_id', $schedule->class_room_id) == $class->id ? 'selected' : '' }}>
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
                    value="{{ old('date', $schedule->date->format('Y-m-d')) }}" required>
                @error('date')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="start_time" class="form-label">Jam Mulai</label>
                    <input type="time" name="start_time" id="start_time"
                        class="form-control @error('start_time') is-invalid @enderror"
                        value="{{ old('start_time', date('H:i', strtotime($schedule->start_time))) }}" required>
                    @error('start_time')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="end_time" class="form-label">Jam Selesai</label>
                    <input type="time" name="end_time" id="end_time"
                        class="form-control @error('end_time') is-invalid @enderror"
                        value="{{ old('end_time', date('H:i', strtotime($schedule->end_time))) }}" required>
                    @error('end_time')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> <strong>Catatan:</strong> Sistem akan mencegah konflik jadwal (guru/kelas
                tidak bisa ada di 2 tempat di waktu yang sama)
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('admin.schedules.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>

@section('scripts')
    <script>
        $(document).ready(function() {
            // Data mapping dari backend (dikirim dari controller)
            // Objek berisi: { teacher_id: [subject_id_1, subject_id_2, ...] }
            // Digunakan untuk validasi pelajaran mana saja yang boleh dipilih untuk guru tertentu
            const teacherSubjects = {
                @foreach ($teachers as $teacher)
                    {{ $teacher->id }}: @json($teacher->subjects->pluck('id')),
                @endforeach
            };

            // Simpan daftar semua pelajaran yang ada
            const allSubjects = @json($subjects);

            // Ambil ID pelajaran yang saat ini tersimpan di database (atau old input jika validasi gagal)
            // Ini penting untuk "memilih kembali" pelajaran yang benar saat halaman diedit
            const currentSubjectId = "{{ old('subject_id', $schedule->subject_id) }}";

            // Inisialisasi Select2
            $('#teacher_id, #subject_id, #class_room_id').select2({
                placeholder: function() {
                    return $(this).data('placeholder');
                },
                allowClear: true,
                width: '100%'
            });

            /**
             * Fungsi untuk mengisi ulang dropdown pelajaran berdasarkan Guru
             * @param {string} teacherId - ID Guru yang dipilih
             * @param {string|null} selectedSubjectId - ID Pelajaran yang harus dipilih otomatis (opsional)
             */
            function populateSubjects(teacherId, selectedSubjectId = null) {
                const $subjectSelect = $('#subject_id');
                // Bersihkan semua opsi yang ada
                $subjectSelect.empty();
                // Tambahkan opsi placeholder
                $subjectSelect.append('<option value="">-- Pilih Mata Pelajaran --</option>');

                // Jika ID Guru valid dan ada di data mapping kita
                if (teacherId && teacherSubjects[teacherId]) {
                    // Ambil array ID pelajaran milik guru tersebut
                    const availableSubjectIds = teacherSubjects[teacherId];

                    // Loop semua pelajaran
                    allSubjects.forEach(function(subject) {
                        // Cek apakah pelajaran ini termasuk yang diajar oleh guru ini
                        if (availableSubjectIds.includes(subject.id)) {
                            // Cek apakah ini pelajaran yang sedang aktif/dipilih
                            const isSelected = selectedSubjectId == subject.id;
                            // Buat opsi baru, set selected jika match
                            const option = new Option(subject.name, subject.id, isSelected, isSelected);
                            $subjectSelect.append(option);
                        }
                    });
                } else {
                    // Jika tidak ada guru ( atau mapping tidak ketemu), tampilkan semua pelajaran sebagai fallback
                    allSubjects.forEach(function(subject) {
                        const isSelected = selectedSubjectId == subject.id;
                        const option = new Option(subject.name, subject.id, isSelected, isSelected);
                        $subjectSelect.append(option);
                    });
                }
                // Refresh Select2
                $subjectSelect.trigger('change');
            }

            // --- PROSES SAAT HALAMAN DIMUAT (INITIAL LOAD) ---
            // Ambil nilai guru yang terpilih saat ini
            const initialTeacherId = $('#teacher_id').val();
            if (initialTeacherId) {
                // Jalankan populateSubjects agar dropdown pelajaran HANYA berisi pelajaran guru tersebut
                // Dan pilih pelajaran yang sedang tersimpan (currentSubjectId)
                populateSubjects(initialTeacherId, currentSubjectId);
            }

            // --- EVENT LISTENER ---
            // Ketika user mengganti pilihan guru
            $('#teacher_id').on('change', function() {
                const teacherId = $(this).val();
                // Jalankan populateSubjects untuk guru baru
                // Pass null sebagai selectedSubjectId karena kita mau user memilih ulang pelajaran
                populateSubjects(teacherId, null);
            });
        });
    </script>
@endsection
@endsection
