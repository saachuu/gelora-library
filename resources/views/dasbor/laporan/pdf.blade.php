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
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        /* Styling untuk Status */
        .status-dipinjam {
            color: #d97706;
            font-weight: bold;
        }

        /* Kuning Gelap */
        .status-dikembalikan {
            color: #16a34a;
            font-weight: bold;
        }

        /* Hijau */
        .status-terlambat {
            color: #dc2626;
            font-weight: bold;
        }

        /* Merah */
    </style>
</head>

<body>
    <div style="text-align: center; margin-bottom: 20px;">
        <h2 style="margin: 0;">Laporan Sirkulasi Perpustakaan</h2>
        <p style="margin: 5px 0;">SMP Gelora Depok</p>
        <p style="font-size: 10px; color: #666;">Dicetak pada: {{ date('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 25%">Peminjam</th>
                <th style="width: 25%">Judul Buku</th>
                <th style="width: 15%">Tgl Pinjam</th>
                <th style="width: 15%">Tgl Kembali</th>
                <th style="width: 10%">Status</th>
                <th style="width: 10%">Denda</th>
            </tr>
        </thead>
        <tbody>
            @forelse($loans as $index => $loan)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $loan->member->full_name }}</strong><br>
                        <span style="color: #666; font-size: 10px;">{{ $loan->member->member_id_number }}</span>
                    </td>
                    <td>{{ $loan->book->title }}</td>

                    <td>{{ \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y') }}</td>

                    <td>
                        {{ $loan->return_date ? \Carbon\Carbon::parse($loan->return_date)->format('d/m/Y') : '-' }}
                    </td>

                    <td>
                        @if ($loan->status == 'Dikembalikan')
                            <span class="status-dikembalikan">Dikembalikan</span>
                        @elseif($loan->status == 'Terlambat')
                            <span class="status-terlambat">Terlambat</span>
                        @else
                            <span class="status-dipinjam">Dipinjam</span>
                        @endif
                    </td>

                    <td>
                        @if ($loan->fine > 0)
                            Rp {{ number_format($loan->fine, 0, ',', '.') }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px;">Tidak ada data sirkulasi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
