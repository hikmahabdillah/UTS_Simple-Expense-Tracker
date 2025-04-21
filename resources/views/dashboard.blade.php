@extends('layouts.template')

@section('content')
    <h1 class="!font-semibold text-xl !font-poppins ms-3 my-3 animate-fade-right animate-once animate-duration-1000">
        Dashboard</h1>
    {{-- <h1 class="!font-medium text-lg !font-poppins ms-3 my-3">Welcome User!</h1> --}}
    <div class="flex justify-center items-center gap-4 mb-4 font-poppins">
        <div
            class="animate-jump-in animate-delay-500 animate-once animate-duration-800 bg-white p-3 rounded-xl shadow-md w-full border border-gray-100 !border-l-4 !border-l-blue-500 ">
            <p class="font-medium mb-1">Current Balance</p>
            <p class="font-semibold text-xl mb-2" id="currentBalance">Rp {{ number_format($balance, 2, ',', '.') }}</p>
        </div>
        <div
            class="animate-jump-in animate-delay-700 animate-once animate-duration-800 bg-white p-3 rounded-xl shadow-md w-full border border-gray-100 !border-l-4 !border-l-red-500">
            <p class="font-medium mb-1">Total Expense</p>
            <p class="font-semibold text-xl mb-2" id="totalExpense">Rp {{ number_format($totalExpense, 2, ',', '.') }}</p>
        </div>
        <div
            class="animate-jump-in animate-delay-1000 animate-once animate-duration-800 bg-white p-3 rounded-xl shadow-md w-full border border-gray-100 !border-l-4 !border-l-green-500">
            <p class="font-medium mb-1">Total Income</p>
            <p class="font-semibold text-xl mb-2" id="totalIncome">Rp {{ number_format($totalIncome, 2, ',', '.') }}</p>
        </div>
    </div>
    <div class="flex justify-center gap-5">
        <div
            class="border border-gray-300 rounded-xl shadow-md p-5 w-full animate-fade-up animate-once animate-duration-1000 animate-delay-1200">
            <h2 class="text-lg !font-semibold mb-3 !font-poppins">Pengeluaran Berdasarkan Kategori</h2>
            <canvas id="categoryExpenseChart" height="100"></canvas>
        </div>
        <div
            class="border border-gray-300 rounded-xl shadow-md p-5 w-full animate-fade-up animate-once animate-duration-1000 animate-delay-1400">
            <h2 class="text-lg mb-3 !font-semibold !font-poppins">Tren Keuangan</h2>
            <canvas id="dailyTrendChart" height="300"></canvas>
        </div>

    </div>
@endsection
@push('js')
    <script>
        // Doughnut Chart - Komposisi Kategori Pengeluaran
        const categoryCtx = document.getElementById('categoryExpenseChart').getContext('2d');
        const categoryExpenseChart = new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($categoryExpenses->pluck('category')) !!}, //ambil daftar nama kategori
                datasets: [{
                    data: {!! json_encode($categoryExpenses->pluck('total')) !!}, // ambil total pengeluaran per kategori
                    backgroundColor: [
                        '#F87171', '#FBBF24', '#34D399',
                        '#60A5FA', '#A78BFA', '#F472B6', '#9CA3AF'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right'
                    }
                }
            }
        });

        // Line Chart - Income vs Expense Sepanjang Waktu
        const dailyCtx = document.getElementById('dailyTrendChart').getContext('2d');
        const dailyChart = new Chart(dailyCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($dailyData->pluck('date')) !!}, // ambil daftar tanggal
                datasets: [{
                    label: 'Income',
                    data: {!! json_encode($dailyData->pluck('income')) !!}, //total pemasukan per hari
                    borderColor: '#4CAF50',
                    backgroundColor: '#4CAF5099',
                    tension: 0.3,
                    fill: false
                }, {
                    label: 'Expense',
                    data: {!! json_encode($dailyData->pluck('expense')) !!}, // total pengeluaran per hari
                    borderColor: '#F44336',
                    backgroundColor: '#F4433699',
                    tension: 0.3,
                    fill: false
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    },
                    x: {
                        ticks: {
                            maxTicksLimit: 10,
                            autoSkip: true
                        }
                    }
                }
            }
        });
    </script>
@endpush
