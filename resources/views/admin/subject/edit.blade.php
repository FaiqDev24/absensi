@extends('templates.app')

@section('content')
    <div class="container mt-5">
        <h5 class="mb-3">Edit Mata Pelajaran</h5>

        <form action="{{ route('admin.subjects.update', ['id' => $subject->id]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Nama Mata Pelajaran</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name', $subject->name) }}">
                @error('name')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="teacher_ids" class="form-label">Guru Pengajar</label>
                <select name="teacher_ids[]" id="teacher_ids" class="form-select" multiple>
                    @foreach ($teachers as $teacher)
                        <option value="{{ $teacher->id }}"
                            {{ in_array($teacher->id, $subject->teachers->pluck('id')->toArray()) ? 'selected' : '' }}>
                            {{ $teacher->name }}
                        </option>
                    @endforeach
                </select>
                <small class="text-muted">Tekan Ctrl (Windows) / Command (Mac) untuk memilih lebih dari satu guru</small>
            </div>

            <button class="btn btn-primary">Perbarui</button>
            <a href="{{ route('admin.subjects.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
@endsection
