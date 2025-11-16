@extends('templates.app')

@section('content')
<div class="container mt-5">
    <h5 class="mb-3">Tambah Mata Pelajaran</h5>

    <form action="{{ route('admin.subjects.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Nama Mata Pelajaran</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
            @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label for="teacher_ids" class="form-label">Guru Pengajar</label>
            <select name="teacher_ids[]" id="teacher_ids" class="form-select" multiple>
                @foreach ($teachers as $teacher)
                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                @endforeach
            </select>
            <small class="text-muted">Tekan Ctrl (Windows) / Command (Mac) untuk memilih lebih dari satu guru</small>
        </div>

        {{-- <div class="mb-3">
            <label class="form-label">Guru (opsional)</label>
            <select name="teacher_id" class="form-select @error('teacher_id') is-invalid @enderror">
                <option value="">-- Pilih Guru --</option>
                @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                        {{ $teacher->name }}
                    </option>
                @endforeach
            </select>
            @error('teacher_id') <div class="text-danger small">{{ $message }}</div> @enderror
        </div> --}}

        <button class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.subjects.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
