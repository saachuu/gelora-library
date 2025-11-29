<x-app-layout>
    <div class="container mx-auto">
        <h1 class="text-3xl font-bold mb-6">Dasbor</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Card 1: Jumlah Buku -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title">Total Buku</h2>
                    <p class="text-4xl font-bold">1,250</p>
                    <div class="card-actions justify-end">
                        <a href="#" class="btn btn-primary">Lihat Detail</a>
                    </div>
                </div>
            </div>

            <!-- Card 2: Jumlah Anggota -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title">Total Anggota</h2>
                    <p class="text-4xl font-bold">350</p>
                    <div class="card-actions justify-end">
                        <a href="#" class="btn btn-primary">Lihat Detail</a>
                    </div>
                </div>
            </div>

            <!-- Card 3: Peminjaman Aktif -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title">Peminjaman Aktif</h2>
                    <p class="text-4xl font-bold">75</p>
                    <div class="card-actions justify-end">
                        <a href="#" class="btn btn-primary">Lihat Detail</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
