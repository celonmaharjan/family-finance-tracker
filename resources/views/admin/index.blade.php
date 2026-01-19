@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Admin Dashboard</div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Total Joint Balance</h5>
                                    <p class="card-text">${{ number_format($totalJointBalance, 2) }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Total Outstanding Loans</h5>
                                    <p class="card-text">${{ number_format($totalOutstandingLoans, 2) }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Total Interest Earned by System</h5>
                                    <p class="card-text">${{ number_format($totalInterestEarnedBySystem, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h4 class="mt-4">Admin Actions</h4>
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Create User</a>
                    <a href="{{ route('admin.deposits.create') }}" class="btn btn-primary">Create Deposit</a>
                    <a href="{{ route('admin.loans.create') }}" class="btn btn-primary">Create Loan</a>
                    <a href="{{ route('admin.loan-payments.create') }}" class="btn btn-primary">Create Loan Payment</a>

                    <h4 class="mt-4">Distribute Interest to Users</h4>
                    <form action="{{ route('admin.distribute-interest') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary">Distribute Monthly Interest</button>
                    </form>

                    <h4 class="mt-4">User Financial Summary</h4>
                    @if ($users->count())
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>User Name</th>
                                    <th>Total Deposited</th>
                                    <th>Total Withdrawn</th>
                                    <th>Outstanding Loan</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>${{ number_format($user->total_deposited_amount, 2) }}</td>
                                        <td>${{ number_format($user->total_withdrawn_amount, 2) }}</td>
                                        <td>${{ number_format($user->outstanding_loan_balance, 2) }}</td>
                                        <td>
                                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-info">Edit</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>No users found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
