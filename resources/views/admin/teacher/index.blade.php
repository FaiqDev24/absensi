@extends('templates.app')

@section('content')
    <div class="container mt-5">
        @if (Session::get('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
        @endif
        <div class="justify-content-end d-flex">
            <a href="{{ route('admin.teachers.trash') }}" class="btn btn-warning me-2">Data Sampah</a>
            <a href="{{ route('admin.teachers.create') }}" class="btn btn-success">Tambah Data</a>
        </div>
        <h5 class="mt-3">Data Guru</h5>
        <table class="table table-bordered">
            <tr>
                <th>#</th>
                <th>NIP</th>
                <th>Nama Guru</th>
                <th>Mata Pelajaran</th>
                <th>Aksi</th>
            </tr>
            @foreach ($teachers as $index => $item)
                <tr>
                    {{-- index dari 0, biar muncul xdari 1 -> +1 --}}
                    <th>{{ $index + 1 }}</th>
                    {{-- name, location dari fillable model cinema --}}
                    <th>{{ $item['nip'] }}</th>
                    <th>{{ @$item->user->name }}</th>
                    <th>
                        {{ @$item->subjects->name ?? 'Tidak ada mata pelajaran' }}
                        {{-- @if ($item->subjects->isNotEmpty())
                            @foreach ($item->subjects as $subject)
                                {{ $subject->name }}@if (!$loop->last)
                                    ,
                                @endif
                            @endforeach
                        @else
                            <em>Tidak ada mata pelajaran</em>
                        @endif --}}
                    </th>
                    <th class="d-flex">
                        {{-- ['id' => $item['id']] untuk mengirim id ke route --}}
                        <a href="{{ route('admin.teachers.edit', ['id' => $item['id']]) }}"
                            class="btn btn-secondary m-2">Edit</a>
                        <form action="{{ route('admin.teachers.destroy', ['id' => $item['id']]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger m-2">Hapus</button>
                        </form>
                    </th>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
