<form action="{{ url('/ajax') }}" method="POST" id="form-add-expense">
    @csrf
    <div id="modal-expense" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Financial Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="title" id="title" class="form-control" required>
                    <small id="error-title" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Amount</label>
                    <input type="number" name="amount" id="amount" class="form-control" step="0.01" required>
                    <small id="error-amount" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Category</label>
                    <select name="category" id="category" class="form-control" required>
                        <option value="">- Select Category -</option>
                        <option value="food">Food</option>
                        <option value="transportation">Transportation</option>
                        <option value="bills">Bills</option>
                        <option value="entertainment">Entertainment</option>
                        <option value="health">Health</option>
                        <option value="education">Education</option>
                        <option value="others">Others</option>
                    </select>
                    <small id="error-category" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Type</label>
                    <select name="type" id="type" class="form-control" required>
                        <option value="">- Select Type -</option>
                        <option value="income">Income</option>
                        <option value="expense">Expense</option>
                    </select>
                    <small id="error-type" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" id="description" class="form-control"></textarea>
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
        // Fungsi format IDR
        function formatted(numberToFormat) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 2
            }).format(numberToFormat);
        }

        $("#form-add-expense").validate({
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
                }
            },
            submitHandler: function(form) {
                const type = $('select[name="type"]').val();
                const amount = $('input[name="amount"]').val();

                console.log(typeof(type))
                console.log(typeof(amount))

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
                            let finalIncome = incomeValue;
                            let finalExpense = expenseValue;

                            console.log(amount)
                            console.log(type)
                            console.log(incomeValue)
                            console.log(expenseValue)
                            console.log(finalIncome)
                            console.log(finalExpense)

                            if (type === 'income') {
                                finalIncome = incomeValue + parseFloat(amount);
                            } else if (type === 'expense') {
                                finalExpense = expenseValue + parseFloat(amount);
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
                            $('.error-text').text('');
                            $.each(response.msgField, function(prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed',
                            text: 'An error occurred while submitting the data.'
                        });
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
