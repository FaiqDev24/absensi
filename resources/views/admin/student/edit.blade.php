@extends('templates.app')

@section('content')
<div class="w-75 d-block mx-auto my-5 p-">
    <h5 class="text-center mb-3">Edit Data Siswa</h5>
    <form action="{{ route('admin.students.update', $student->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nis" class="form-label">NIS</label>
            <input type="text" name="nis" class="form-control" value="{{ old('nis', $student->nis) }}" required>
        </div>

        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" class="form-control" value="{{ old('username', $student->username) }}" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" class="form-control" value="{{ old('password', $student->password) }}" required>
        </div>

        <div class="mb-3">
            <label for="name" class="form-label">Nama Siswa</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $student->name) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Gender</label>
            <select name="gender" class="form-select" required>
                <option value="">-- Pilih Gender --</option>
                <option value="L" {{ $student->gender == 'L' ? 'selected' : '' }}>Laki-laki</option>
                <option value="P" {{ $student->gender == 'P' ? 'selected' : '' }}>Perempuan</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Kelas</label>
            <select name="class_room_id" class="form-select" required>
                <option value="">-- Pilih Kelas --</option>
                @foreach ($classRooms as $class)
                    <option value="{{ $class->id }}" {{ $student->class_room_id == $class->id ? 'selected' : '' }}>
                        {{ $class->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="grade" class="form-label">Grade (optional)</label>
            <input type="text" name="grade" class="form-control" value="{{ old('grade', $student->grade) }}">
        </div>

        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
