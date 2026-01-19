@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h3>Welcome, {{ Auth::user()->name }}!</h3>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Total Deposited</h5>
                                    <p class="card-text">${{ number_format($totalDeposited, 2) }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Total Withdrawn (Loans)</h5>
                                    <p class="card-text">${{ number_format($totalWithdrawn, 2) }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Outstanding Loan Balance</h5>
                                    <p class="card-text">${{ number_format($outstandingLoan, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h4 class="mt-4">Your Loans</h4>
                    @if ($loans->count())
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Loan ID</th>
                                    <th>Principal Amount</th>
                                    <th>Remaining Balance</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($loans as $loan)
                                    <tr>
                                        <td>{{ $loan->id }}</td>
                                        <td>${{ number_format($loan->principal_amount, 2) }}</td>
                                        <td>${{ number_format($loan->remaining_balance, 2) }}</td>
                                        <td>{{ ucfirst($loan->status) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>You have no loans.</p>
                    @endif

                    <h4 class="mt-4">Transaction History</h4>
                    @if ($transactions->count())
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $transaction)
                                    <tr>
                                        <td>{{ ucfirst(str_replace('_', ' ', $transaction->type)) }}</td>
                                        <td>${{ number_format($transaction->amount, 2) }}</td>
                                        <td>{{ $transaction->created_at->format('M d, Y H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $transactions->links() }}
                    @else
                        <p>No transactions yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
