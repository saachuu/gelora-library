<!DOCTYPE html>
<html>
<head>
    <title>Laporan Peminjaman</title>
    <style>
        body { font-family: 'sans-serif'; }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Laporan Peminjaman</h2>
    <p>Periode: {{ $startDate }} - {{ $endDate }}</p>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Judul Buku</th>
                <th>Anggota</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($loans as $loan)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $loan->book->title }}</td>
                    <td>{{ $loan->member->name }}</td>
                    <td>{{ $loan->loan_date }}</td>
                    <td>{{ $loan->returned_at ?? 'Belum Kembali' }}</td>
                    <td>{{ $loan->is_returned ? 'Dikembalikan' : 'Dipinjam' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">Tidak ada data untuk periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
