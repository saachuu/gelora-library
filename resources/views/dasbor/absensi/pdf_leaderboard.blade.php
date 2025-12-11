<!DOCTYPE html>
<html>

<head>
    <title>Peringkat Siswa Terrajin</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .rank {
            font-weight: bold;
            text-align: center;
        }

        .poin {
            text-align: center;
            font-weight: bold;
            color: #2563eb;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2 style="margin: 0;">TOP SISWA TERRAJIN</h2>
        <p style="margin: 5px 0;">Perpustakaan SMP Gelora Depok</p>
        <p style="font-size: 10px;">Periode: {{ date('F Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 10%; text-align: center;">Peringkat</th>
                <th style="width: 40%">Nama Siswa</th>
                <th style="width: 20%">NIS</th>
                <th style="width: 15%">Kelas</th>
                <th style="width: 15%; text-align: center;">Total Poin</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($members as $index => $member)
                <tr>
                    <td class="rank">#{{ $index + 1 }}</td>
                    <td>{{ $member->full_name }}</td>
                    <td>{{ $member->member_id_number }}</td>
                    <td>{{ $member->position }}</td>
                    <td class="poin">{{ $member->total_points }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
