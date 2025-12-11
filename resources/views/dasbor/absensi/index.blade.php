@extends('layouts.admin')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="attendanceHandler()">

        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-gray-800">Buku Tamu Digital</h1>
            <p class="text-gray-500 mt-2">Scan kehadiran siswa untuk mencatat durasi kunjungan.</p>
            <div class="mt-6">
                <a href="{{ route('dasbor.absensi.leaderboard') }}"
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                    🏆 Lihat Peringkat Siswa
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm text-center font-bold text-lg animate-pulse"
                role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 mb-10 relative">
            <div class="p-8">
                <form action="{{ route('dasbor.absensi.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-2 text-center">Cari Siswa (NIS /
                            Nama)</label>
                        <input type="text" x-model="query" @input.debounce.300ms="searchMember()"
                            placeholder="Ketik nama siswa..."
                            class="w-full text-center text-xl py-4 text-black rounded-full border-2 border-indigo-100 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                            autocomplete="off" autofocus>

                        <div x-show="results.length > 0"
                            class="absolute z-50 w-full mt-2 bg-white shadow-xl rounded-xl border border-gray-100 max-h-60 overflow-auto">
                            <template x-for="member in results" :key="member.id">
                                <div @click="selectMember(member)"
                                    class="cursor-pointer p-4 hover:bg-indigo-50 border-b border-gray-50 flex justify-between items-center transition">
                                    <span x-text="member.text" class="font-bold text-gray-700"></span>
                                    <span class="text-xs text-gray-400">Pilih</span>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div x-show="selectedMember"
                        class="mt-6 bg-gray-50 rounded-xl p-6 text-center border border-gray-200 transition"
                        style="display: none;">
                        <p class="text-gray-500 text-sm uppercase tracking-wide font-semibold">Siswa Terpilih</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1" x-text="selectedMember?.text"></h3>

                        <input type="hidden" name="member_id" :value="selectedMember?.id">

                        <div class="py-6">
                            <template x-if="loading">
                                <span class="text-gray-400">Memeriksa status...</span>
                            </template>

                            <template x-if="!loading && !isCheckedIn">
                                <div class="inline-flex items-center px-4 py-2 rounded-full bg-gray-200 text-gray-700">
                                    <span class="w-3 h-3 bg-gray-400 rounded-full mr-2"></span>
                                    Status: Belum Masuk
                                </div>
                            </template>

                            <template x-if="!loading && isCheckedIn">
                                <div
                                    class="inline-flex items-center px-4 py-2 rounded-full bg-green-100 text-green-800 border border-green-200">
                                    <span class="w-3 h-3 bg-green-500 rounded-full mr-2 animate-ping"></span>
                                    Status: Sedang Berkunjung (Masuk jam <span x-text="checkInTime"></span>)
                                </div>
                            </template>
                        </div>

                        <button type="submit"
                            :class="isCheckedIn ? 'bg-red-600 hover:bg-red-700' : 'bg-indigo-600 hover:bg-indigo-700'"
                            class="w-full text-white font-bold py-4 px-6 rounded-xl shadow-lg transform transition hover:scale-[1.02] text-xl flex justify-center items-center">

                            <span x-text="isCheckedIn ? '🚪 CHECK-OUT (KELUAR)' : '👋 CHECK-IN (MASUK)'"></span>
                        </button>

                        <button type="button" @click="reset()"
                            class="mt-4 text-gray-400 hover:text-gray-600 text-sm underline">Batalkan / Ganti Siswa</button>
                    </div>

                </form>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h3 class="font-semibold text-gray-700">Kunjungan Hari Ini</h3>
            </div>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Siswa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Masuk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keluar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Durasi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($todayVisits as $visit)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                {{ $visit->member->full_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $visit->check_in_at->format('H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                {{ $visit->check_out_at ? $visit->check_out_at->format('H:i') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($visit->check_out_at)
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $visit->got_point ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $visit->duration_minutes }} menit {{ $visit->got_point ? '(+1 Poin)' : '' }}
                                    </span>
                                @else
                                    <span class="text-green-600 text-xs font-bold animate-pulse">Sedang Berkunjung...</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-400 text-sm">Belum ada pengunjung hari
                                ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4">
                {{ $todayVisits->links() }}
            </div>
        </div>
    </div>

    <script>
        function attendanceHandler() {
            return {
                query: '',
                results: [],
                selectedMember: null,
                isCheckedIn: false,
                checkInTime: null,
                loading: false,

                async searchMember() {
                    if (this.query.length < 1) {
                        this.results = [];
                        return;
                    }

                    // Fetch ke route API Absensi yang baru
                    let res = await fetch(`{{ route('api.absensi.search') }}?q=${this.query}`);
                    this.results = await res.json();
                },

                async selectMember(member) {
                    this.selectedMember = member;
                    this.query = '';
                    this.results = [];
                    this.loading = true;

                    // Cek status kunjungan member ini
                    try {
                        let res = await fetch(`/dasbor/api/absensi/status/${member.id}`);
                        let data = await res.json();

                        this.isCheckedIn = data.is_checked_in;
                        this.checkInTime = data.check_in_time;
                    } catch (e) {
                        console.error(e);
                    }

                    this.loading = false;
                },

                reset() {
                    this.selectedMember = null;
                    this.isCheckedIn = false;
                    this.checkInTime = null;
                }
            }
        }
    </script>
@endsection
