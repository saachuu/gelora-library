<!DOCTYPE html>
<html>

<head>
    <title>Laporan Kunjungan Harian</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #e0e0e0;
            text-align: center;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .center {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2 style="margin: 0;">LAPORAN KUNJUNGAN PERPUSTAKAAN</h2>
        <p style="margin: 5px 0;">SMP GELORA DEPOK</p>
        <p style="font-size: 10px;">Tanggal: {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 25%">Nama Siswa</th>
                <th style="width: 15%">Kelas</th>
                <th style="width: 15%">Masuk</th>
                <th style="width: 15%">Keluar</th>
                <th style="width: 25%">Keperluan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($visits as $index => $visit)
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td>
                        <b>{{ $visit->member->full_name }}</b><br>
                        <span style="color: #555; font-size: 9px;">{{ $visit->member->member_id_number }}</span>
                    </td>
                    <td class="center">{{ $visit->member->position }}</td>
                    <td class="center">{{ \Carbon\Carbon::parse($visit->check_in_at)->format('H:i') }}</td>
                    <td class="center">
                        {{ $visit->check_out_at ? \Carbon\Carbon::parse($visit->check_out_at)->format('H:i') : '-' }}
                    </td>

                    <td>{{ $visit->notes ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="center">Tidak ada data kunjungan hari ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
