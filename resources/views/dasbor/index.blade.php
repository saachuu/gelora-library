@extends('layouts.admin')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Welcome Section -->
        <div class="mb-8 flex flex-col md:flex-row justify-between items-center bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-8 text-white shadow-lg">
            <div>
                <h1 class="text-3xl font-bold mb-2">Selamat Datang, {{ Auth::user()->name }}! 👋</h1>
                <p class="text-indigo-100">Berikut adalah ringkasan aktivitas perpustakaan hari ini.</p>
            </div>
            <div class="mt-4 md:mt-0">
                <span class="px-4 py-2 bg-white/20 rounded-lg backdrop-blur-sm text-sm font-medium">
                    {{ now()->format('l, d F Y') }}
                </span>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Buku -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-blue-50 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-gray-400">Total Koleksi</span>
                </div>
                <h3 class="text-2xl font-bold text-gray-800">{{ number_format($bookCount) }}</h3>
                <p class="text-sm text-gray-500 mt-1">Judul Buku</p>
            </div>

            <!-- Anggota Aktif -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-green-50 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-gray-400">Pengguna</span>
                </div>
                <h3 class="text-2xl font-bold text-gray-800">{{ number_format($memberCount) }}</h3>
                <p class="text-sm text-gray-500 mt-1">Anggota Aktif</p>
            </div>

            <!-- Peminjaman Aktif -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-yellow-50 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-gray-400">Sirkulasi</span>
                </div>
                <h3 class="text-2xl font-bold text-gray-800">{{ number_format($activeLoanCount) }}</h3>
                <p class="text-sm text-gray-500 mt-1">Sedang Dipinjam</p>
            </div>

            <!-- Terlambat -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-red-50 rounded-lg">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-gray-400">Warning</span>
                </div>
                <h3 class="text-2xl font-bold text-gray-800">{{ number_format($overdueLoanCount) }}</h3>
                <p class="text-sm text-gray-500 mt-1">Buku Terlambat</p>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <!-- Monthly Loans Chart -->
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Statistik Peminjaman Bulanan</h3>
                <div class="relative h-72">
                    <canvas id="monthlyLoansChart"></canvas>
                </div>
            </div>

            <!-- Category Distribution -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Kategori Populer</h3>
                <div class="relative h-72 flex justify-center">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Bottom Section: Popular Books & Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Popular Books -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800">Buku Terpopuler</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($popularBooks as $index => $book)
                            <div class="flex items-center p-3 hover:bg-gray-50 rounded-lg transition-colors">
                                <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center bg-indigo-100 text-indigo-600 rounded-full font-bold">
                                    #{{ $index + 1 }}
                                </div>
                                <div class="ml-4 flex-1">
                                    <h4 class="text-sm font-semibold text-gray-900 truncate">{{ $book->title }}</h4>
                                    <p class="text-xs text-gray-500">{{ $book->author }}</p>
                                </div>
                                <div class="text-sm font-medium text-gray-600">
                                    {{ $book->loans_count }}x Dipinjam
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800">Aktivitas Terbaru</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Anggota</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Buku</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($recentActivities as $loan)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $loan->member->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $loan->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 truncate max-w-xs">{{ $loan->book->title }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $loan->status === 'Dipinjam' ? 'bg-yellow-100 text-yellow-800' : 
                                               ($loan->status === 'Kembali' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                                            {{ $loan->status }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Monthly Loans Chart
            const ctxLoans = document.getElementById('monthlyLoansChart').getContext('2d');
            new Chart(ctxLoans, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                    datasets: [{
                        label: 'Peminjaman',
                        data: @json($monthlyData),
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
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

            // Category Chart
            const ctxCategory = document.getElementById('categoryChart').getContext('2d');
            const categoryData = @json($categoryStats);
            
            new Chart(ctxCategory, {
                type: 'doughnut',
                data: {
                    labels: categoryData.map(item => item.name),
                    datasets: [{
                        data: categoryData.map(item => item.count),
                        backgroundColor: [
                            '#4f46e5', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899'
                        ],
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
                                usePointStyle: true,
                                padding: 20
                            }
                        }
                    },
                    cutout: '70%'
                }
            });
        });
    </script>
@endsection