@extends('layouts.app')

@section('title', 'Sign Up')

@section('content')
    <div class="container">
        <form id="submitForm" method="POST" action="{{ route('auth.signup') }}" onsubmit="return validateForm()">
            @csrf
            @if(session('error'))
                <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
            @endif
            <div class="form-group required" >
                <label for="name">Full Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('fullname') }}" required>
            </div>
            <div class="form-group required" >
                <label for="number">Phone Number</label>
                <input type="text" class="form-control" id="number" name="phonenumber" value="{{ old('number') }}" required>
            </div>
            <div class="form-group required" >
                <label for="address">Address</label>
                <input type="text" class="form-control" id="address" name="address" value="{{ old('address') }}" required>
            </div>
            <div class="form-group required" >
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
            </div>
            <div class="form-group required">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group required">
                <label for="confirm-password">Confirm Password</label>
                <input type="password" class="form-control" id="confirm-password" name="confirm-password" required>
            </div>
            <div class="form-group required">
                <label for="voterid">Voter ID</label>
                <input type="text" class="form-control" id="voter-id" name="voterid" required>
            </div>

            <div class="form-group pt-1">
                <button class="btn btn-success btn-block">Sign Up</button>
            </div>
        </form>

        <p class="small-xl pt-3 text-center">
            <span class="text-muted">Already a member?</span>
            <a href="{{ route('login') }}">Sign in</a>
        </p>
    </div>
@endsection
