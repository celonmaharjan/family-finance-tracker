@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar (Hidden on mobile by default) -->
        <nav class="col-md-2 d-none d-md-block bg-light sidebar py-4">
            <div class="position-sticky">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-grid-fill me-2"></i>{{ __('Dashboard') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.users.create') }}">
                            <i class="bi bi-person-plus-fill me-2"></i>{{ __('Create User') }}
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">{{ __('Admin Dashboard') }}</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <form action="{{ route('admin.dashboard') }}" method="GET" class="d-flex align-items-center">
                        <label for="year" class="me-2 fw-bold text-muted">{{ __('Fiscal Year:') }}</label>
                        <select name="year" id="year" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">{{ __('All Time') }}</option>
                            @foreach($availableYears as $year)
                                <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>

            <!-- Dashboard Stats Cards -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="card text-white bg-primary h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-0">{{ __('Joint Balance') }}</h6>
                                    <h3 class="mt-2 text-wrap" style="word-break: break-all;">${{ number_format($totalJointBalance, 2) }}</h3>
                                    <small class="opacity-75">Current</small>
                                </div>
                                <div><i class="bi bi-bank fs-1"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card text-white bg-warning h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-0">{{ __('Outstanding Loans') }}</h6>
                                    <h3 class="mt-2 text-wrap" style="word-break: break-all;">${{ number_format($totalOutstandingLoans, 2) }}</h3>
                                    <small class="opacity-75">{{ $loanInterestRate }}%/yr</small>
                                </div>
                                <div><i class="bi bi-percent fs-1"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card text-white bg-info h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-0">{{ __('Interest Earned') }}</h6>
                                    <h3 class="mt-2 text-wrap" style="word-break: break-all;">${{ number_format($totalInterestEarnedBySystem, 2) }}</h3>
                                    <small class="opacity-75">{{ $selectedYear ? "In $selectedYear" : "All Time" }}</small>
                                </div>
                                <div><i class="bi bi-graph-up-arrow fs-1"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card text-white bg-success h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-0">{{ __('Deposit Rate') }}</h6>
                                    <h3 class="mt-2">{{ $depositInterestRate }}%</h3>
                                    <small class="opacity-75">{{ number_format($depositInterestRate / 12, 2) }}%/mo</small>
                                </div>
                                <div><i class="bi bi-piggy-bank fs-1"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Transaction Widget -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="bi bi-lightning-charge-fill me-2"></i>{{ __('Quick Transaction') }}</h5>
                </div>
                <div class="card-body">
                    <ul class="nav nav-pills nav-fill mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pills-deposit-tab" data-bs-toggle="pill" data-bs-target="#pills-deposit" type="button" role="tab" aria-selected="true"><i class="bi bi-plus-circle me-1"></i> Deposit</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-loan-tab" data-bs-toggle="pill" data-bs-target="#pills-loan" type="button" role="tab" aria-selected="false"><i class="bi bi-dash-circle me-1"></i> Loan</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-repay-tab" data-bs-toggle="pill" data-bs-target="#pills-repay" type="button" role="tab" aria-selected="false"><i class="bi bi-arrow-return-left me-1"></i> Repay</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <!-- Deposit Form -->
                        <div class="tab-pane fade show active" id="pills-deposit" role="tabpanel">
                            <form action="{{ route('admin.deposits.store') }}" method="POST">
                                @csrf
                                <div class="input-group mb-3">
                                    <select class="form-select form-select-lg" name="user_id" required>
                                        <option value="" selected disabled>Select User...</option>
                                        @foreach ($users as $u)
                                            @if($u->role !== 'admin')
                                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" class="form-control form-control-lg" name="amount" placeholder="Amount" required>
                                    <button class="btn btn-primary" type="submit">Add Deposit</button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Loan Form -->
                        <div class="tab-pane fade" id="pills-loan" role="tabpanel">
                            <form action="{{ route('admin.loans.store') }}" method="POST">
                                @csrf
                                <div class="input-group mb-3">
                                    <select class="form-select form-select-lg" name="user_id" required>
                                        <option value="" selected disabled>Select User...</option>
                                        @foreach ($users as $u)
                                            @if($u->role !== 'admin')
                                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" class="form-control form-control-lg" name="amount" placeholder="Loan Amount" required>
                                    <button class="btn btn-warning" type="submit">Give Loan</button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Repay Form -->
                        <div class="tab-pane fade" id="pills-repay" role="tabpanel">
                            <form action="{{ route('admin.loans.repay') }}" method="POST">
                                @csrf
                                <div class="input-group mb-3">
                                    <select class="form-select form-select-lg" name="user_id" required>
                                        <option value="" selected disabled>Select User...</option>
                                        @foreach ($users as $u)
                                            @if($u->role !== 'admin' && $u->outstanding_loan_balance > 0)
                                                <option value="{{ $u->id }}">{{ $u->name }} (Debt: ${{ number_format($u->outstanding_loan_balance, 2) }})</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="payment_type" id="payPrincipal" value="principal" checked>
                                        <label class="form-check-label" for="payPrincipal">Principal Payment</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="payment_type" id="payInterest" value="interest_only">
                                        <label class="form-check-label" for="payInterest">Interest Only</label>
                                    </div>
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" class="form-control form-control-lg" name="amount" placeholder="Repayment Amount" required>
                                    <button class="btn btn-success" type="submit">Repay Loan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User List - Responsive Cards -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">{{ __('Members') }} 
                    @if($selectedYear)
                        <small class="text-muted fs-6">({{ $selectedYear }})</small>
                    @endif
                </h4>
                 <form action="{{ route('admin.distribute-interest') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-secondary"><i class="bi bi-calculator-fill me-1"></i>Distribute Interest</button>
                </form>
            </div>

            @if ($users->count())
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
                    @foreach ($users as $user)
                        @if ($user->role !== 'admin')
                            <div class="col">
                                <div class="card h-100 shadow-sm border-0">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div>
                                                <h5 class="card-title fw-bold mb-0 text-primary">{{ $user->name }}</h5>
                                                <small class="text-muted">{{ $user->email }}</small>
                                            </div>
                                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-light text-muted rounded-circle"><i class="bi bi-pencil"></i></a>
                                        </div>
                                        
                                        <div class="row g-2 mb-3">
                                            <div class="col-6">
                                                <div class="p-2 border rounded bg-light">
                                                    <small class="text-uppercase text-muted d-block" style="font-size: 0.7rem;">Deposited</small>
                                                    <span class="fw-bold text-success">${{ number_format($user->total_deposited_amount, 2) }}</span>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="p-2 border rounded bg-light">
                                                    <small class="text-uppercase text-muted d-block" style="font-size: 0.7rem;">Interest Earned <span class="text-info">(4%)</span></small>
                                                    <span class="fw-bold text-info">${{ number_format($user->total_interest_earned, 2) }}</span>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="p-2 border rounded bg-light d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <small class="text-uppercase text-muted d-block" style="font-size: 0.7rem;">Loan Balance</small>
                                                        <span class="fw-bold {{ $user->outstanding_loan_balance > 0 ? 'text-danger' : 'text-success' }}">
                                                            ${{ number_format($user->outstanding_loan_balance, 2) }}
                                                        </span>
                                                    </div>
                                                    @if($user->outstanding_loan_balance > 0)
                                                        <i class="bi bi-exclamation-circle-fill text-danger"></i>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="p-2 border rounded bg-light">
                                                    <small class="text-uppercase text-muted d-block" style="font-size: 0.7rem;">Loan Interest <span class="text-warning">(12%)</span></small>
                                                    <span class="fw-bold text-warning">${{ number_format($user->loan_interest_accrued, 2) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="d-grid gap-2">
                                            <button class="btn btn-outline-primary btn-sm" type="button" 
                                                onclick="fillQuickTransaction('{{ $user->id }}', 'deposit')">
                                                <i class="bi bi-plus-circle me-1"></i> Deposit
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @else
                <div class="alert alert-info" role="alert">
                    {{ __('No users found.') }}
                </div>
            @endif

        </main>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function fillQuickTransaction(userId, type) {
        // Trigger tab change using Bootstrap API or fallback
        var triggerEl = document.querySelector('#pills-' + type + '-tab');
        if (triggerEl) {
             // Try to use bootstrap global if available, or just click it
             if (typeof bootstrap !== 'undefined') {
                var tab = new bootstrap.Tab(triggerEl);
                tab.show();
             } else {
                 triggerEl.click();
             }
        }
        
        // Find the select in the active tab and set the value
        var activeTab = document.querySelector('#pills-' + type);
        if (activeTab) {
            var select = activeTab.querySelector('select[name="user_id"]');
            if(select) {
                select.value = userId;
                // Highlight the select to show it changed
                select.focus();
            }
        }
        
        // Scroll to top
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
</script>
@endpush