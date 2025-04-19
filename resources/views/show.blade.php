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
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $page->title ?? 'Expense Details' }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-striped">
                    <tr>
                        <th>Title</th>
                        <td>{{ $expense->title }}</td>
                    </tr>
                    <tr>
                        <th>Amount</th>
                        <td>Rp {{ number_format($expense->amount, 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Category</th>
                        <td>{{ ucfirst($expense->category) }}</td>
                    </tr>
                    <tr>
                        <th>Type</th>
                        <td>{{ ucfirst($expense->type) }}</td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td>{{ $expense->description ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $expense->created_at->format('d M Y H:i') }}</td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-secondary">Close</button>
            </div>
        </div>
    </div>
@endempty
