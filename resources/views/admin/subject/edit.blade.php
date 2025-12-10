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
                    value="{{ old('name', $subject->name) }}" required>
                @error('name')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="teacher_ids" class="form-label">Guru Pengajar</label>
                <select name="teacher_ids[]" id="teacher_ids" class="form-select" multiple>
                    @foreach ($teachers as $teacher)
                        <option value="{{ $teacher->id }}"
                            {{ $subject->teachers->contains($teacher->id) ? 'selected' : '' }}>
                            {{ $teacher->user->name }}
                        </option>
                    @endforeach
                </select>
                <small class="text-muted">Pilih satu atau lebih guru pengajar</small>
            </div>

            <button class="btn btn-primary">Perbarui</button>
            <a href="{{ route('admin.subjects.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#teacher_ids').select2({
                placeholder: "Pilih guru pengajar",
                allowClear: true,
                width: '100%'
            });
        });
    </script>
@endsection
