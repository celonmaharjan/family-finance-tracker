@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Create User') }}</div>

                <div class="card-body">
                    <form action="{{ route('admin.users.store') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="name">{{ __('Name') }}</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="email">{{ __('Email') }}</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="password">{{ __('Password') }}</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="password_confirmation">{{ __('Confirm Password') }}</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="role">{{ __('Role') }}</label>
                            <select class="form-control" id="role" name="role" required>
                                <option value="member">{{ __('Member') }}</option>
                                <option value="admin">{{ __('Admin') }}</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">{{ __('Create User') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
