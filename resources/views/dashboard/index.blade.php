@extends('layouts.app')

@section('content')
    <div class="container">
        <h3 class="mb-4">{{ __('Welcome') }}, {{ Auth::user()->name }}!</h3>

        <div class="row">
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title">{{ __('Total Deposited') }}</h5>
                                <h3>${{ number_format($totalDeposited, 2) }}</h3>
                            </div>
                            <div>
                                <i class="bi bi-cash-stack fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-secondary mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title">{{ __('Total Withdrawn') }}</h5>
                                <h3>${{ number_format($totalWithdrawn, 2) }}</h3>
                            </div>
                            <div>
                                <i class="bi bi-wallet2 fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-danger mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title">{{ __('Outstanding Loan') }}</h5>
                                <h3>${{ number_format($outstandingLoan, 2) }}</h3>
                            </div>
                            <div>
                                <i class="bi bi-exclamation-triangle-fill fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-4" id="loans">
            <div class="card-header">
                <h4><i class="bi bi-journal-text me-2"></i>{{ __('Your Loans') }}</h4>
            </div>
            <div class="card-body">
                @if ($loans->count())
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>{{ __('Loan ID') }}</th>
                                    <th>{{ __('Principal Amount') }}</th>
                                    <th>{{ __('Remaining Balance') }}</th>
                                    <th>{{ __('Status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($loans as $loan)
                                    <tr>
                                        <td>{{ $loan->id }}</td>
                                        <td>${{ number_format($loan->principal_amount, 2) }}</td>
                                        <td>${{ number_format($loan->remaining_balance, 2) }}</td>
                                        <td><span
                                                class="badge bg-{{ $loan->status === 'active' ? 'success' : 'warning' }}">{{ ucfirst(__($loan->status)) }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p>{{ __('You have no loans.') }}</p>
                @endif
            </div>
        </div>

        <div class="card mt-4" id="transactions">
            <div class="card-header">
                <h4><i class="bi bi-clock-history me-2"></i>{{ __('Transaction History') }}</h4>
            </div>
            <div class="card-body">
                @if ($transactions->count())
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Date') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $transaction)
                                    <tr>
                                        <td>{{ ucfirst(str_replace('_', ' ', __($transaction->type))) }}</td>
                                        <td>${{ number_format($transaction->amount, 2) }}</td>
                                        <td>{{ $transaction->created_at->format('M d, Y H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $transactions->links() }}
                @else
                    <p>{{ __('No transactions yet.') }}</p>
                @endif
            </div>
        </div>
    </div>
@endsection