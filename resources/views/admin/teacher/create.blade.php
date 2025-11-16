@extends('templates.app')

@section('content')
 <div class="w-75 d-block mx-auto my-5 p-">
    <h5 class="text-center mb-3">Tambah Data Guru</h5>
    <form action="{{ route('admin.teachers.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="nip" class="form-label">NIP</label>
            <input type="text" name="nip" id="nip" class="form-control @error ('nip') is-invalid @enderror">
            @error('nip')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="mb-3">
            <label for="name" class="form-label">Nama Guru</label>
            <input type="text" name="name" id="name" class="form-control @error ('name') is-invalid @enderror">
            @error('name')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="text" name="email" id="email" class="form-control @error ('nip') is-invalid @enderror">
            @error('nip')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control @error ('nip') is-invalid @enderror">
            @error('nip')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Kirim</button>
    </form>
 </div>
@endsection
