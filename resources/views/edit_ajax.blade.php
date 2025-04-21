@empty($expense)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Error</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Error!</h5>
                    The data you are looking for was not found.
                </div>
                <a href="{{ url('/expenses') }}" class="btn btn-warning">Back</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/' . $expense->id . '/update_ajax') }}" method="POST" id="form-edit">
        {{-- Menambahkan token khusus (_token) ke form sebagai input tersembunyi (hidden input).
Token ini akan diverifikasi oleh Laravel saat form disubmit.
Jika token tidak valid atau tidak ada, maka request akan ditolak oleh server Laravel demi keamana --}}
        @csrf
        @method('PUT') {{-- Menggunakan method PUT untuk mengubah data --}}
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Expense Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Title</label>
                        <input value="{{ $expense->title }}" type="text" name="title" id="title"
                            class="form-control" required>
                        <small id="error-title" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Amount</label>
                        <input value="{{ $expense->amount }}" type="number" name="amount" id="amount"
                            class="form-control" step="0.01" required>
                        <small id="error-amount" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Category</label>
                        <select name="category" id="category" class="form-control" required>
                            <option value="">- Select Category -</option>
                            @foreach (['food', 'transportation', 'bills', 'entertainment', 'health', 'education', 'others'] as $cat)
                                <option value="{{ $cat }}" {{ $cat == $expense->category ? 'selected' : '' }}>
                                    {{ ucfirst($cat) }}
                                </option>
                            @endforeach
                        </select>
                        <small id="error-category" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Type</label>
                        <select name="type" id="type" class="form-control" required>
                            <option value="income" {{ $expense->type == 'income' ? 'selected' : '' }}>Income</option>
                            <option value="expense" {{ $expense->type == 'expense' ? 'selected' : '' }}>Expense</option>
                        </select>
                        <small id="error-type" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" id="description" class="form-control" rows="3">{{ $expense->description }}</textarea>
                        <small id="error-description" class="error-text form-text text-danger"></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-warning">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </form>

    <script>
        $(document).ready(function() {
            // variable untuk menyimpan data sebelumnya sebelum di edit
            const prevType = "{{ $expense->type }}";
            const prevAmount = parseFloat("{{ $expense->amount }}");

            // Fungsi format IDR
            function formatted(numberToFormat) {
                const formatted = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 2
                }).format(numberToFormat);

                return formatted;
            }
            $("#form-edit").validate({
                rules: {
                    title: {
                        required: true,
                        maxlength: 255
                    },
                    amount: {
                        required: true,
                        number: true,
                        min: 0
                    },
                    category: {
                        required: true
                    },
                    type: {
                        required: true
                    },
                    description: {
                        maxlength: 500
                    }
                },
                submitHandler: function(form) {
                    const type = $('select[name="type"]').val();
                    const amount = $('input[name="amount"]').val();

                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize(),
                        success: function(response) {
                            if (response.status) {
                                $('#myModal').modal('hide');
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: response.message
                                });
                                dataExpenses.ajax.reload();

                                // Hitung ulang nilai akhir
                                // /untuk  menyimpan nilai final yang akan ditampilkan
                                let finalIncome = incomeValue;
                                let finalExpense = expenseValue;

                                // perhitungan untuk pemasukkan dan pengeluaran setelah di edit
                                if (prevType === 'income' && type === 'income') {
                                    // nilai final dikurangi dengan nilai sebelumnya dan ditambahkan dengan nilai baru
                                    finalIncome = incomeValue - prevAmount + parseFloat(
                                        amount);
                                } else if (prevType === 'expense' && type === 'expense') {
                                    // nilai final dikurangi dengan nilai sebelumnya dan ditambahkan dengan nilai baru
                                    finalExpense = expenseValue - prevAmount + parseFloat(
                                        amount);
                                } else if (prevType === 'income' && type === 'expense') {
                                    finalIncome = incomeValue - prevAmount;
                                    finalExpense = expenseValue + parseFloat(amount);
                                } else if (prevType === 'expense' && type === 'income') {
                                    finalExpense = expenseValue - prevAmount;
                                    finalIncome = incomeValue + parseFloat(amount);
                                }

                                // Update tampilan
                                totalIncome.text(formatted(finalIncome));
                                totalExpense.text(formatted(finalExpense));

                                // nilai saldo terbaru(setelah terjadi pembaruan data)
                                const newBalance = finalIncome - finalExpense;
                                currentBalance.text(formatted(newBalance));

                                // Update nilai global yang nantinya akan digunakan oleh halaman lain
                                incomeValue = finalIncome;
                                expenseValue = finalExpense;
                                balanceValue = newBalance;
                            } else {
                                $('.error-text').text('');
                                $.each(response.msgField, function(prefix, val) {
                                    $('#error-' + prefix).text(val[0]);
                                });
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error Occurred',
                                    text: response.message
                                });
                            }
                        }
                    });
                    return false;
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
    </script>
@endempty
