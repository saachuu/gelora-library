<!DOCTYPE html>
<html>

<head>
    <title>Laporan Keaktifan Siswa</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }

        .header p {
            margin: 2px 0;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .center {
            text-align: center;
        }

        .badge {
            background: #eee;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Laporan Keaktifan Siswa</h1>
        <p>Perpustakaan SMP Gelora Depok</p>
        <p>Dicetak pada: {{ now()->translatedFormat('d F Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="center" style="width: 40px;">No</th>
                <th>Nama Siswa</th>
                <th>NIS</th>
                <th>Kelas/Jabatan</th>
                <th class="center">Total Poin</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($members as $index => $member)
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td>{{ $member->full_name }}</td>
                    <td>{{ $member->member_id_number }}</td>
                    <td>{{ $member->position }}</td>
                    <td class="center">
                        <span class="badge">{{ $member->total_points }} Poin</span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 40px; text-align: right;">
        <p>Depok, {{ now()->translatedFormat('d F Y') }}</p>
        <p>Kepala Perpustakaan</p>
        <br><br><br>
        <p>( .................................... )</p>
    </div>
</body>

</html>
