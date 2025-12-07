@extends('templates.app')

@section('content')
    <div class="container mt-5">
        <h5 class="text-center mb-3">Edit Kehadiran</h5>

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('teacher.attendance.update', $attendance->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Siswa</label>
                        <input type="text" class="form-control" value="{{ $attendance->student->name }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kelas</label>
                        <input type="text" class="form-control" value="{{ $attendance->classRoom->name ?? '-' }}"
                            readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mata Pelajaran</label>
                        <input type="text" class="form-control" value="{{ $attendance->subject->name ?? '-' }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="text" class="form-control" value="{{ $attendance->date->format('d/m/Y') }}"
                            readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status Kehadiran</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" value="hadir"
                                    {{ $attendance->status == 'hadir' ? 'checked' : '' }} required>
                                <label class="form-check-label">Hadir</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" value="sakit"
                                    {{ $attendance->status == 'sakit' ? 'checked' : '' }} required>
                                <label class="form-check-label">Sakit</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" value="izin"
                                    {{ $attendance->status == 'izin' ? 'checked' : '' }} required>
                                <label class="form-check-label">Izin</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" value="alpha"
                                    {{ $attendance->status == 'alpha' ? 'checked' : '' }} required>
                                <label class="form-check-label">Alpha</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Catatan tambahan (optional)">{{ $attendance->notes }}</textarea>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('teacher.attendance.index') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Update Kehadiran</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
