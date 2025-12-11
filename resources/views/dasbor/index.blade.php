@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Selamat Datang, {{ Auth::user()->name }}! 👋</h1>
            <p class="text-gray-500 mt-2">Berikut adalah ringkasan statistik perpustakaan Anda hari ini.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-blue-100 p-3 rounded-lg text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                    <span class="text-gray-400 text-sm font-medium">Total Anggota</span>
                </div>
                <div class="text-3xl font-bold text-gray-800">{{ $total_anggota }}</div>
                <div class="text-sm text-green-500 mt-2 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18">
                        </path>
                    </svg>
                    Aktif
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-indigo-100 p-3 rounded-lg text-indigo-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                            </path>
                        </svg>
                    </div>
                    <span class="text-gray-400 text-sm font-medium">Koleksi Buku</span>
                </div>
                <div class="text-3xl font-bold text-gray-800">{{ $total_buku }}</div>
                <div class="text-sm text-gray-500 mt-2">Judul buku</div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-yellow-100 p-3 rounded-lg text-yellow-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span class="text-gray-400 text-sm font-medium">Sedang Dipinjam</span>
                </div>
                <div class="text-3xl font-bold text-gray-800">{{ $total_peminjaman }}</div>
                <div class="text-sm text-yellow-600 mt-2">Buku belum kembali</div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-green-100 p-3 rounded-lg text-green-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span class="text-gray-400 text-sm font-medium">Total Kembali</span>
                </div>
                <div class="text-3xl font-bold text-gray-800">{{ $total_dikembalikan }}</div>
                <div class="text-sm text-gray-500 mt-2">Riwayat pengembalian</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 lg:col-span-2">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                    </svg>
                    Tren Peminjaman (6 Bulan Terakhir)
                </h3>
                <div class="relative h-64 w-full">
                    <canvas id="loanTrendChart"></canvas>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Kategori Buku</h3>
                <div class="relative h-64 w-full flex justify-center">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 lg:col-span-2">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                        </path>
                    </svg>
                    Top 5 Buku Terpopuler
                </h3>
                <div class="relative h-64 w-full">
                    <canvas id="popularBooksChart"></canvas>
                </div>
            </div>

            <div class="bg-gradient-to-br from-indigo-600 to-purple-700 p-6 rounded-xl shadow-lg text-white">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold flex items-center">
                        <svg class="w-6 h-6 mr-2 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z">
                            </path>
                        </svg>
                        Siswa Terrajin
                    </h3>
                    <span class="bg-white/20 text-xs px-2 py-1 rounded-full">Top 3</span>
                </div>

                <div class="space-y-4">
                    @forelse($top_siswa as $index => $rank)
                        <div
                            class="flex items-center bg-white/10 p-3 rounded-lg backdrop-blur-sm border border-white/10 hover:bg-white/20 transition">
                            <div
                                class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-full font-bold text-lg
                            {{ $index == 0 ? 'bg-yellow-400 text-yellow-900' : '' }}
                            {{ $index == 1 ? 'bg-gray-300 text-gray-800' : '' }}
                            {{ $index == 2 ? 'bg-orange-400 text-orange-900' : '' }}">
                                #{{ $index + 1 }}
                            </div>

                            <div class="ml-3 flex-1 min-w-0">
                                <p class="text-sm font-semibold truncate">{{ $rank->member->full_name }}</p>
                                <p class="text-xs text-indigo-100 truncate">{{ $rank->member->position }}
                                    ({{ $rank->member->member_id_number }})
                                </p>
                            </div>

                            <div class="text-right">
                                <span class="block text-lg font-bold text-white">{{ $rank->total_poin }}</span>
                                <span class="text-xs text-indigo-200">Poin</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-indigo-200">
                            <svg class="w-12 h-12 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p>Belum ada data kunjungan.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // --- 1. Chart Tren Peminjaman ---
        const ctxTrend = document.getElementById('loanTrendChart').getContext('2d');
        new Chart(ctxTrend, {
            type: 'line',
            data: {
                labels: {!! json_encode($label_tren) !!},
                datasets: [{
                    label: 'Jumlah Peminjaman',
                    data: {!! json_encode($data_tren) !!},
                    borderColor: '#4F46E5',
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            borderDash: [2, 4]
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // --- 2. Chart Kategori ---
        const ctxCat = document.getElementById('categoryChart').getContext('2d');
        new Chart(ctxCat, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($label_kategori) !!},
                datasets: [{
                    data: {!! json_encode($data_kategori) !!},
                    backgroundColor: ['#4F46E5', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            font: {
                                size: 10
                            }
                        }
                    }
                }
            }
        });

        // --- 3. Chart Buku Populer (Horizontal Bar) ---
        const ctxPop = document.getElementById('popularBooksChart').getContext('2d');
        new Chart(ctxPop, {
            type: 'bar',
            data: {
                labels: {!! json_encode($label_populer) !!},
                datasets: [{
                    label: 'Total Dipinjam',
                    data: {!! json_encode($data_populer) !!},
                    backgroundColor: '#F59E0B',
                    borderRadius: 6,
                    barThickness: 30
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: {
                            borderDash: [2, 4]
                        }
                    },
                    y: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    </script>
@endsection
