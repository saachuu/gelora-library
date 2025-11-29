<x-app-layout>
    <div class="container mx-auto">
        <h1 class="text-3xl font-bold mb-6">Manajemen Buku</h1>

        <div class="mb-4">
            <a href="#" class="btn btn-primary">Tambah Buku Baru</a>
        </div>

        <div class="overflow-x-auto">
            <table class="table w-full">
                <!-- head -->
                <thead>
                    <tr>
                        <th></th>
                        <th>Judul</th>
                        <th>Penulis</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- row 1 -->
                    <tr>
                        <th>1</th>
                        <td>Bumi Manusia</td>
                        <td>Pramoedya Ananta Toer</td>
                        <td>5</td>
                        <td>
                            <a href="#" class="btn btn-sm btn-info">Detail</a>
                            <a href="#" class="btn btn-sm btn-warning">Edit</a>
                            <a href="#" class="btn btn-sm btn-error">Hapus</a>
                        </td>
                    </tr>
                    <!-- row 2 -->
                    <tr>
                        <th>2</th>
                        <td>Laskar Pelangi</td>
                        <td>Andrea Hirata</td>
                        <td>3</td>
                        <td>
                            <a href="#" class="btn btn-sm btn-info">Detail</a>
                            <a href="#" class="btn btn-sm btn-warning">Edit</a>
                            <a href="#" class="btn btn-sm btn-error">Hapus</a>
                        </td>
                    </tr>
                    <!-- row 3 -->
                    <tr>
                        <th>3</th>
                        <td>Cantik Itu Luka</td>
                        <td>Eka Kurniawan</td>
                        <td>7</td>
                        <td>
                            <a href="#" class="btn btn-sm btn-info">Detail</a>
                            <a href="#" class="btn btn-sm btn-warning">Edit</a>
                            <a href="#" class="btn btn-sm btn-error">Hapus</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
