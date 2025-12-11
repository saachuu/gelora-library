@extends('layouts.admin')
@section('title', 'Peringkat Rajin')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">🏆 Peringkat Rajin Bulan Ini</h1>
                <p class="text-gray-500 text-sm mt-1">Siswa dengan poin keaktifan tertinggi.</p>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('dasbor.absensi.leaderboard.pdf') }}" target="_blank"
                    class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition font-bold flex items-center shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    Export PDF
                </a>

                <a href="{{ route('dasbor.absensi.index') }}"
                    class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition font-medium">
                    Kembali
                </a>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-24">
                                Rank</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama
                                Siswa</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kelas
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Total
                                Poin</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($top_students as $index => $student)
                            <tr class="hover:bg-indigo-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($index == 0)
                                        <span class="text-2xl">🥇</span>
                                    @elseif($index == 1)
                                        <span class="text-2xl">🥈</span>
                                    @elseif($index == 2)
                                        <span class="text-2xl">🥉</span>
                                    @else
                                        <span class="font-bold text-gray-500 ml-2">#{{ $index + 1 }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">{{ $student->full_name }}</div>
                                    <div class="text-xs text-gray-500">NIS: {{ $student->member_id_number }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-50 text-blue-700 border border-blue-100">
                                        {{ $student->position }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span
                                        class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-bold bg-indigo-600 text-white shadow-sm">
                                        {{ $student->total_points }} Poin
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                                    Belum ada siswa yang mendapatkan poin bulan ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
