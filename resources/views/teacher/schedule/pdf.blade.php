<!DOCTYPE html>
<html>

<head>
    <title>Jadwal Mengajar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>Jadwal Mengajar</h2>
        <p>Tanggal Cetak: {{ date('d/m/Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 5%">No</th>
                <th class="text-center">Hari</th>
                <th class="text-center">Waktu</th>
                <th class="text-center">Mata Pelajaran</th>
                <th class="text-center">Kelas</th>
            </tr>
        </thead>
        <tbody>
            @forelse($schedules as $index => $schedule)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $schedule->day }}</td>
                    <td class="text-center">
                        {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} -
                        {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                    </td>
                    <td>{{ $schedule->subject->name ?? '-' }}</td>
                    <td class="text-center">{{ $schedule->classRoom->name ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada jadwal mengajar.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
