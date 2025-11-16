@extends('templates.app')

@section('content')
 <div class="w-75 d-block mx-auto my-5 p-">
    <h5 class="text-center mb-3">Edit Data Kelas</h5>
    <form action="{{route('admin.classrooms.update', ['id' => $classRoom['id']])}}" method="POST">
        @csrf
        {{--menimpa method="POST" html menjadi PUT--}}
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Nama Kelas</label>
            <input type="text" name="name" id="name" class="form-control @error ('name') is-invalid @enderror" value="{{ $classRoom['name'] }}">
            @error('name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Kirim</button>
    </form>
 </div>
@endsection
