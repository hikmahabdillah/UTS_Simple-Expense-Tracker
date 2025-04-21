@extends('layouts.template')

@section('content')
    <h1 class="!font-semibold text-xl !font-poppins ms-3 my-3 animate-fade-right animate-once animate-duration-1000">
        Financial Data</h1>
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
    <div
        class="card card-outline card-primary pt-2 font-poppins animate-fade-up animate-once animate-duration-1000 animate-delay-1200">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('/create_ajax') }}')" class="btn btn-sm btn-success mt-1">Add
                    Data</button>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <table class="table table-bordered table-striped table-hover table-sm w-full font-poppins" id="table_expenses">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Title</th>
                        <th>Amount</th>
                        <th>Category</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <div id="myModal" class="modal fade animate shake font-poppins" tabindex="-1" role="dialog" data-backdrop="static"
        data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('js')
    <script>
        // fungsi untuk keseluruhan modal di url manapun
        function modalAction(url = '') {
            $('#myModal').load(url, function() { //  Mengisi isi modal (#myModal) dengan konten dari URL yang diberikan.
                $('#myModal').modal('show');
            });
        }

        // mengambil elemen
        let currentBalance = $('#currentBalance');
        let totalExpense = $('#totalExpense');
        let totalIncome = $('#totalIncome');

        // fungsi untuk merubah format string rupiah menjadi sebuah value bertipe float
        function parseRupiah(rupiahString) {
            if (!rupiahString) return 0;
            return parseFloat(
                rupiahString
                .replace(/[^0-9,-]/g, '') // hilangkan selain angka, koma, dan minus
                .replace(/\./g, '') // hapus titik ribuan
                .replace(',', '.') // ubah koma ke titik agar bisa dibaca JS
            );
        }

        // nilai global untuk nilai saldo, totalPengeluaran, totalPemasukkan yang sudah diparsing
        let balanceValue = parseRupiah(currentBalance.text());
        let expenseValue = parseRupiah(totalExpense.text());
        let incomeValue = parseRupiah(totalIncome.text());

        let dataExpenses;
        $(document).ready(function() {
            dataExpenses = $('#table_expenses').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('/') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        className: 'text-center',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'title',
                    },
                    {
                        data: 'amount',
                    },
                    {
                        data: 'category',
                    },
                    {
                        data: 'type',
                    },
                    {
                        data: 'description',
                    },
                    {
                        data: 'aksi',
                        className: 'text-center',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });
    </script>
@endpush
