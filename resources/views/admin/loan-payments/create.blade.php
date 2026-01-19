@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create Loan Payment</div>

                <div class="card-body">
                    <form action="{{ route('admin.loan-payments.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="loan_id">Loan</label>
                            <select class="form-control" id="loan_id" name="loan_id" required>
                                @foreach ($loans as $loan)
                                    <option value="{{ $loan->id }}">Loan #{{ $loan->id }} (User: {{ $loan->user->name }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="amount">Amount</label>
                            <input type="number" step="0.01" class="form-control" id="amount" name="amount" required>
                        </div>
                        <div class="form-group">
                            <label for="payment_type">Payment Type</label>
                            <select class="form-control" id="payment_type" name="payment_type" required>
                                <option value="interest">Interest</option>
                                <option value="principal">Principal</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Create Loan Payment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
