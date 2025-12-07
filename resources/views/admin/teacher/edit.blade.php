@extends('templates.app')

@section('content')
    <div class="w-75 d-block mx-auto my-5 p-">
        <h5 class="text-center mb-3">Edit Data Guru</h5>
        <form action="{{ route('admin.teachers.update', ['id' => $teacher['id']]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="nip" class="form-label">NIP</label>
                <input type="text" name="nip" id="nip" class="form-control @error('nip') is-invalid @enderror"
                    value="{{ $teacher['nip'] }}">
                @error('nip')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Nama Guru</label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                    value="{{ $teacher->user->name }}">
                @error('name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Kirim</button>
        </form>
    </div>
@endsection
