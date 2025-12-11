<!DOCTYPE html>
<html>

<head>
    <title>Laporan Perpustakaan</title>
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
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #eee;
        }

        h1 {
            text-align: center;
        }
    </style>
</head>

<body>
    <h1>Laporan Peminjaman Perpustakaan</h1>
    <p>Dicetak pada: {{ now()->format('d M Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal Pinjam</th>
                <th>Nama Siswa</th>
                <th>NIS</th>
                <th>Judul Buku</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($loans as $index => $loan)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($loan->loan_date)->format('d M Y') }}</td>
                    <td>{{ $loan->member->full_name }}</td>
                    <td>{{ $loan->member->member_id_number }}</td>
                    <td>{{ $loan->book->title }}</td>
                    <td>{{ $loan->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
