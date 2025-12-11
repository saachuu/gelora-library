@extends('layouts.admin')
@section('title', 'Buku Tamu')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="attendanceSystem()">

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Buku Tamu Digital</h1>
                <p class="text-gray-500 text-sm mt-1">Sistem Check-in & Check-out Perpustakaan.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('dasbor.absensi.leaderboard') }}"
                    class="inline-flex items-center px-4 py-2 bg-indigo-50 text-indigo-700 border border-indigo-100 rounded-lg hover:bg-indigo-100 transition font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                    Peringkat Rajin
                </a>
                <a href="{{ route('dasbor.absensi.pdf') }}" target="_blank"
                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-bold shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    Export PDF
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 sticky top-24">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Input Kunjungan</h3>

                    <form action="{{ route('dasbor.absensi.store') }}" method="POST" autocomplete="off">
                        @csrf

                        <div class="mb-4 relative">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Cari Nama / NIS</label>
                            <div class="relative">
                                <input type="text" x-model="query" @input.debounce.300ms="searchMember()"
                                    class="w-full rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 py-3 pl-10 text-lg"
                                    placeholder="Ketik nama..." autofocus>
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </div>

                            <div x-show="results.length > 0"
                                class="absolute z-20 w-full bg-white border border-gray-200 rounded-xl shadow-xl mt-1 max-h-60 overflow-y-auto"
                                style="display: none;">
                                <template x-for="item in results" :key="item.id">
                                    <div @click="selectMember(item)"
                                        class="px-4 py-3 hover:bg-indigo-50 cursor-pointer border-b border-gray-50 flex justify-between items-center">
                                        <div>
                                            <p class="font-bold text-gray-800" x-text="item.name"></p>
                                            <p class="text-xs text-gray-500" x-text="item.text"></p>
                                        </div>
                                        <svg class="h-5 w-5 text-gray-300" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <input type="hidden" name="member_id" x-model="selectedId">

                        <div x-show="selectedId" class="animate-fade-in-up" style="display: none;">

                            <div class="p-4 bg-indigo-50 rounded-xl border border-indigo-100 text-center mb-4">
                                <p class="text-xs text-indigo-500 uppercase tracking-wide font-semibold">Siswa Terpilih</p>
                                <h2 class="text-xl font-bold text-indigo-900 mt-1" x-text="selectedName"></h2>

                                <div class="mt-3">
                                    <template x-if="isCheckedIn">
                                        <div class="inline-flex flex-col items-center">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">
                                                <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                                                SEDANG DI PERPUSTAKAAN
                                            </span>
                                            <span class="text-xs text-green-600 mt-1">Masuk jam: <span
                                                    x-text="checkInTime"></span></span>
                                        </div>
                                    </template>
                                    <template x-if="!isCheckedIn">
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-200 text-gray-600">
                                            BELUM CHECK-IN
                                        </span>
                                    </template>
                                </div>
                            </div>

                            <div x-show="!isCheckedIn" class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Keperluan Kunjungan</label>
                                <select name="notes"
                                    class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="Membaca Buku">📖 Membaca Buku</option>
                                    <option value="Mengerjakan Tugas">📝 Mengerjakan Tugas</option>
                                    <option value="Meminjam/Kembali">📚 Meminjam/Kembali</option>
                                    <option value="Diskusi Kelompok">🗣️ Diskusi Kelompok</option>
                                    <option value="Lainnya">... Lainnya</option>
                                </select>
                            </div>

                            <button type="submit"
                                :class="isCheckedIn ? 'bg-red-600 hover:bg-red-700 ring-red-300' :
                                    'bg-indigo-600 hover:bg-indigo-700 ring-indigo-300'"
                                class="w-full text-white py-4 rounded-xl font-bold text-lg shadow-md hover:shadow-lg transform transition active:scale-95 focus:ring-4 flex justify-center items-center gap-2">

                                <template x-if="isCheckedIn">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                        </path>
                                    </svg>
                                </template>
                                <template x-if="!isCheckedIn">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                                        </path>
                                    </svg>
                                </template>

                                <span x-text="isCheckedIn ? 'CHECK-OUT SEKARANG' : 'CHECK-IN MASUK'"></span>
                            </button>

                            <button type="button" @click="reset()"
                                class="w-full mt-4 text-sm text-gray-400 hover:text-gray-600 underline transition">Batalkan
                                Pilihan</button>
                        </div>

                        <div x-show="!selectedId"
                            class="text-center py-10 text-gray-400 border-2 border-dashed border-gray-200 rounded-xl bg-gray-50">
                            <svg class="w-12 h-12 mx-auto mb-2 opacity-30" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                            <p class="text-sm font-medium">Pilih siswa untuk memulai</p>
                        </div>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-full">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                        <h3 class="font-bold text-gray-700 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Aktivitas Hari Ini
                        </h3>
                        <span
                            class="text-xs font-medium bg-white border border-gray-200 text-gray-600 px-3 py-1 rounded-full shadow-sm">
                            {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}
                        </span>
                    </div>

                    <div class="overflow-x-auto flex-1">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Siswa</th>
                                    <th
                                        class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Masuk</th>
                                    <th
                                        class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Keluar</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Keperluan</th>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($todayVisits as $visit)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-bold text-gray-900">{{ $visit->member->full_name }}
                                            </div>
                                            <div class="text-xs text-gray-500">{{ $visit->member->position }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span
                                                class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-mono font-bold">
                                                {{ \Carbon\Carbon::parse($visit->check_in_at)->format('H:i') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if ($visit->check_out_at)
                                                <span
                                                    class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-mono font-bold">
                                                    {{ \Carbon\Carbon::parse($visit->check_out_at)->format('H:i') }}
                                                </span>
                                            @else
                                                <span class="text-gray-400 text-xs">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                                {{ $visit->notes ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            @if ($visit->check_out_at)
                                                <div class="text-xs text-gray-500">
                                                    {{ $visit->duration_minutes }} Menit
                                                    @if ($visit->got_point)
                                                        <span class="ml-1 text-yellow-600 font-bold"
                                                            title="Dapat Poin">★</span>
                                                    @endif
                                                </div>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2 py-1 rounded text-xs font-bold text-green-600 bg-green-50 animate-pulse">
                                                    Online
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-gray-400 bg-gray-50">
                                            <div class="flex flex-col items-center">
                                                <svg class="w-10 h-10 mb-2 opacity-50" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <p class="text-sm">Belum ada kunjungan hari ini.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-4 border-t border-gray-100 bg-white">
                        {{ $todayVisits->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function attendanceSystem() {
            return {
                query: '',
                results: [],
                selectedId: '',
                selectedName: '',
                isCheckedIn: false,
                checkInTime: '',

                async searchMember() {
                    if (this.query.length < 2) {
                        this.results = [];
                        return;
                    }

                    try {
                        let res = await fetch(`{{ route('api.absensi.search') }}?q=${this.query}`);
                        this.results = await res.json();
                    } catch (e) {
                        console.error("Gagal mencari siswa:", e);
                    }
                },

                async selectMember(item) {
                    this.selectedId = item.id;
                    this.selectedName = item.name;
                    this.query = '';
                    this.results = []; // Tutup dropdown

                    // Cek status siswa ini ke server
                    try {
                        let statusRes = await fetch(`/dasbor/api/absensi/status/${item.id}`);
                        let statusData = await statusRes.json();

                        this.isCheckedIn = statusData.is_checked_in;
                        this.checkInTime = statusData.check_in_time;
                    } catch (e) {
                        console.error("Gagal cek status:", e);
                    }
                },

                reset() {
                    this.selectedId = '';
                    this.selectedName = '';
                    this.isCheckedIn = false;
                    this.query = '';
                }
            }
        }
    </script>
@endsection
