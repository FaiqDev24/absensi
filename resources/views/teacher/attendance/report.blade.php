@extends('templates.app')

@section('content')
    <div class="container mt-5">
        <h5 class="text-center mb-3">Laporan Kehadiran Siswa</h5>

        <!-- Filter -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('teacher.attendance.report') }}">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Kelas</label>
                            <select name="class_room_id" class="form-select" required>
                                <option value="">Pilih Kelas</option>
                                @foreach ($classRooms as $class)
                                    <option value="{{ $class->id }}"
                                        {{ request('class_room_id') == $class->id ? 'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Mata Pelajaran (Optional)</label>
                            <select name="subject_id" class="form-select">
                                <option value="">Semua Mata Pelajaran</option>
                                @foreach ($subjects as $subj)
                                    <option value="{{ $subj->id }}"
                                        {{ request('subject_id') == $subj->id ? 'selected' : '' }}>
                                        {{ $subj->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>Bulan</label>
                            <select name="month" class="form-select" required>
                                @for ($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}"
                                        {{ request('month', date('n')) == $m ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>Tahun</label>
                            <select name="year" class="form-select" required>
                                @for ($y = date('Y') - 2; $y <= date('Y') + 1; $y++)
                                    <option value="{{ $y }}"
                                        {{ request('year', date('Y')) == $y ? 'selected' : '' }}>
                                        {{ $y }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if ($reportData)
            <!-- Export Button -->
            <div class="mb-3">
                <a href="{{ route('teacher.attendance.export', request()->all()) }}" class="btn btn-success">
                    <i class="fas fa-file-excel"></i> Export to CSV
                </a>
                <a href="{{ route('teacher.attendance.index') }}" class="btn btn-secondary">Kembali</a>
            </div>

            <!-- Report Table -->
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-3">
                        Laporan Kehadiran -
                        {{ request('month') ? date('F', mktime(0, 0, 0, request('month'), 1)) : '' }}
                        {{ request('year') }}
                    </h6>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIS</th>
                                    <th>Nama Siswa</th>
                                    <th class="text-center bg-success text-white">Hadir</th>
                                    <th class="text-center bg-warning">Sakit</th>
                                    <th class="text-center bg-info text-white">Izin</th>
                                    <th class="text-center bg-danger text-white">Alpha</th>
                                    <th class="text-center">Total</th>
                                    <th class="text-center">Persentase Hadir</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reportData as $i => $data)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $data['student']->nis ?? '-' }}</td>
                                        <td>{{ $data['student']->user->name }}</td>
                                        <td class="text-center">{{ $data['hadir'] }}</td>
                                        <td class="text-center">{{ $data['sakit'] }}</td>
                                        <td class="text-center">{{ $data['izin'] }}</td>
                                        <td class="text-center">{{ $data['alpha'] }}</td>
                                        <td class="text-center"><strong>{{ $data['total'] }}</strong></td>
                                        <td class="text-center">
                                            @if ($data['total'] > 0)
                                                <strong>{{ number_format(($data['hadir'] / $data['total']) * 100, 1) }}%</strong>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">Tidak ada data kehadiran.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if ($reportData && count($reportData) > 0)
                                <tfoot>
                                    <tr class="table-secondary">
                                        <th colspan="3" class="text-end">Total:</th>
                                        <th class="text-center">{{ collect($reportData)->sum('hadir') }}</th>
                                        <th class="text-center">{{ collect($reportData)->sum('sakit') }}</th>
                                        <th class="text-center">{{ collect($reportData)->sum('izin') }}</th>
                                        <th class="text-center">{{ collect($reportData)->sum('alpha') }}</th>
                                        <th class="text-center">{{ collect($reportData)->sum('total') }}</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        @elseif(request('class_room_id'))
            <div class="alert alert-info">
                Silakan pilih kelas, bulan, dan tahun untuk menampilkan laporan.
            </div>
        @endif
    </div>
@endsection
