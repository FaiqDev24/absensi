@extends('templates.app')

@section('content')
 <div class="w-75 d-block mx-auto my-5 p-">
    <h5 class="text-center mb-3">Edit Data Guru</h5>
    <form action="{{route('admin.teachers.update', ['id' => $teacher['id']])}}" method="POST">
        @csrf
        {{--menimpa method="POST" html menjadi PUT--}}
        @method('PUT')
        <div class="mb-3">
            <label for="nip" class="form-label">NIP</label>
            <input type="text" name="nip" id="nip" class="form-control @error ('nip') is-invalid @enderror" value="{{ $teacher['nip'] }}">
            @error('nip')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="mb-3">
            <label for="name" class="form-label">Nama Guru</label>
            <input type="text" name="name" id="name" class="form-control @error ('name') is-invalid @enderror" value="{{ $teacher->user->name}}">
            @error('name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Mata Pelajaran</label>
            <select name="subject_id" class="form-select" required>
                <option value="">-- Pilih Mapel --</option>
                @foreach ($subject as $sb)
                    <option value="{{ $sb->id }}" {{ $teacher->subject_id == $sb->id ? 'selected' : '' }}>
                        {{ $sb->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Kirim</button>
    </form>
 </div>
@endsection
