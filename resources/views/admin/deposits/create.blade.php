@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create Deposit</div>

                <div class="card-body">
                    <form action="{{ route('admin.deposits.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="user_id">User</label>
                            <select class="form-control" id="user_id" name="user_id" required>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="amount">Amount</label>
                            <input type="number" step="0.01" class="form-control" id="amount" name="amount" required>
                        </div>
                        <div class="form-group">
                            <label for="payment_method">Payment Method (Optional)</label>
                            <input type="text" class="form-control" id="payment_method" name="payment_method">
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Create Deposit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
