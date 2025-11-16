@extends('templates.app')

@section('content')
<div class="w-75 d-block mx-auto my-5 p-">
    <h5 class="text-center mb-3">Tambah Data Siswa</h5>
    <form action="{{ route('admin.students.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="nis" class="form-label">NIS</label>
            <input type="text" name="nis" class="form-control @error('nis') is-invalid @enderror" placeholder="Masukkan NIS" value="{{ old('nis') }}">
            @error('nis')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" placeholder="Masukkan Username" value="{{ old('username') }}">
            @error('username')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan Password" value="{{ old('password') }}">
            @error('password')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label for="name" class="form-label">Nama Siswa</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Masukkan Nama" value="{{ old('name') }}">
            @error('name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Jenis Kelamin</label>
            <select name="gender" class="form-select @error('gender') is-invalid @enderror" >
                <option value="">-- Pilih Jenis Kelamin --</option>
                <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
            </select>
            @error('gender')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Kelas</label>
            <select name="class_room_id" class="form-select @error('class_room_id') is-invalid @enderror">
                <option value="">-- Pilih Kelas --</option>
                @foreach ($classRooms as $class)
                    <option value="{{ $class->id }}" {{ old('class_room_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                @endforeach
            </select>
            @error('class_room_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label for="grade" class="form-label">Grade</label>
            <input type="text" name="grade" class="form-control @error('grade') is-invalid @enderror" placeholder="Masukkan Grade jika ada" value="{{ old('grade') }}">
            @error('grade')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Kirim</button>
        <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
