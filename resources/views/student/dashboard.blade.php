@extends('templates.app')

@section('content')
    <div class="container mt-5">
        @if (Session::get('success'))
            <div class="alert alert-success">{{ Session::get('success') }} <b>Selamat Datang, {{ Auth::user()->name }}</b>
            </div>
        @endif

        <div class="card shadow-sm mt-4">
            <div class="card-header bg-primary text-white">
                Informasi Siswa
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <img src="{{ Auth::user()->profile_photo ? asset('storage/' . Auth::user()->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}"
                            alt="Profile Photo" class="img-fluid rounded border">
                    </div>
                    <div class="col-md-8">
                        <table class="table table-borderless">
                            <tr>
                                <th width="30%">Nama</th>
                                <td>: {{ Auth::user()->name }}</td>
                            </tr>
                            <tr>
                                <th>NIS</th>
                                <td>: {{ $student->nis ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Kelas</th>
                                <td>: {{ $student->classRoom->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Jenis Kelamin</th>
                                <td>: {{ $student->gender ?? '-' }}</td>
                            </tr>

                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mt-4">
            <div class="card-header bg-secondary text-white">
                Statistik Kehadiran
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Stats Column (Left) -->
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="card bg-success text-white h-100">
                                    <div class="card-body text-center d-flex flex-column justify-content-center">
                                        <h3>{{ $stats['hadir'] ?? 0 }}</h3>
                                        <p class="mb-0">Hadir</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card bg-warning text-white h-100">
                                    <div class="card-body text-center d-flex flex-column justify-content-center">
                                        <h3>{{ $stats['sakit'] ?? 0 }}</h3>
                                        <p class="mb-0">Sakit</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card bg-info text-white h-100">
                                    <div class="card-body text-center d-flex flex-column justify-content-center">
                                        <h3>{{ $stats['izin'] ?? 0 }}</h3>
                                        <p class="mb-0">Izin</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card bg-danger text-white h-100">
                                    <div class="card-body text-center d-flex flex-column justify-content-center">
                                        <h3>{{ $stats['alpha'] ?? 0 }}</h3>
                                        <p class="mb-0">Alpha</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Chart Column (Right) -->
                    <div class="col-md-6 d-flex align-items-center justify-content-center">
                        <div style="width: 80%; max-width: 300px;">
                            <canvas id="attendanceChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    </div>

    {{-- CDN for Chart js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @stack('scripts')
    <script>
        const ctx = document.getElementById('attendanceChart');

        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Hadir', 'Sakit', 'Izin', 'Alpha'],
                datasets: [{
                    label: 'Statistik Kehadiran',
                    data: [
                        {{ $stats['hadir'] ?? 0 }},
                        {{ $stats['sakit'] ?? 0 }},
                        {{ $stats['izin'] ?? 0 }},
                        {{ $stats['alpha'] ?? 0 }}
                    ],
                    backgroundColor: [
                        '#198754', // Success (Hadir)
                        '#ffc107', // Warning (Sakit)
                        '#0dcaf0', // Info (Izin)
                        '#dc3545' // Danger (Alpha)
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
    </script>
@endsection
