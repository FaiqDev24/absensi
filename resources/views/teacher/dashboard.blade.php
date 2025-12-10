@extends('templates.app')

@section('content')
    <div class="container mt-5">
        @if (Session::get('success'))
            <div class="alert alert-success">{{ Session::get('success') }} <b>Selamat Datang, {{ Auth::user()->name }}</b>
            </div>
        @endif
        <p class="text-muted">Jadwal mengajar hari ini</p>
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-primary text-white">
                Jadwal Hari Ini
            </div>
            <div class="card-body">
                @if ($todaySchedules && $todaySchedules->isNotEmpty())
                    <table class="table table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Mata Pelajaran</th>
                                <th>Kelas</th>
                                <th>Jam</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($todaySchedules as $i => $schedule)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $schedule->subject->name }}</td>
                                    <td>{{ $schedule->classRoom->name }}</td>
                                    <td>{{ $schedule->start_time->format('H:i') }} -
                                        {{ $schedule->end_time->format('H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-center text-muted">
                        Tidak ada jadwal mengajar hari ini 📘
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
