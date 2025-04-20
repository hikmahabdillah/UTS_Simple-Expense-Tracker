@empty($expense)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Error</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
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
    <form action="{{ url('/expenses/' . $expense->id . '/delete_ajax') }}" method="POST" id="form-delete">
        @csrf
        @method('DELETE')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Expense</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Confirmation</h5>
                        Are you sure you want to delete this data?
                    </div>
                    <table class="table table-bordered table-sm">
                        <tr>
                            <th>Title</th>
                            <td>{{ $expense->title }}</td>
                        </tr>
                        <tr>
                            <th>Amount</th>
                            <td id="amount">Rp {{ number_format($expense->amount, 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Category</th>
                            <td>{{ ucfirst($expense->category) }}</td>
                        </tr>
                        <tr>
                            <th>Type</th>
                            <td id="type">{{ ucfirst($expense->type) }}</td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                </div>
            </div>
        </div>
    </form>

    <script>
        $(document).ready(function() {
            function formatted(numberToFormat) {
                const formatted = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 2
                }).format(numberToFormat);

                return formatted;
            }

            function parseRupiah(rupiahString) {
                if (!rupiahString) return 0;
                return parseFloat(
                    rupiahString
                    .replace(/[^0-9,-]/g, '') // hilangkan selain angka, koma, dan minus
                    .replace(/\./g, '') // hapus titik ribuan
                    .replace(',', '.') // ubah koma ke titik agar bisa dibaca JS
                );
            }

            $("#form-delete").validate({
                rules: {},
                submitHandler: function(form) {
                    const type = $('#type').text().trim().toLowerCase();
                    const amount = parseRupiah($('#amount').text());

                    console.log(type)
                    console.log(amount)

                    $.ajax({
                        url: form.action,
                        type: 'DELETE',
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

                                let finalIncome = incomeValue;
                                let finalExpense = expenseValue;

                                if (type === 'income') {
                                    finalIncome = incomeValue - amount;
                                } else if (type === 'expense') {
                                    finalExpense = expenseValue - amount;
                                }

                                const newBalance = finalIncome - finalExpense;

                                // Tampilkan hasil ke UI
                                totalIncome.text(formatted(finalIncome));
                                totalExpense.text(formatted(finalExpense));
                                currentBalance.text(formatted(newBalance));

                                // Update nilai global
                                incomeValue = finalIncome;
                                expenseValue = finalExpense;
                                balanceValue = newBalance;
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Failed',
                                    text: response.message
                                });
                            }
                        }
                    });
                    return false;
                }
            });
        });
    </script>
@endempty
