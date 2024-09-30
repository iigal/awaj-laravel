@extends('layouts.app')

@section('title', 'Verify OTP')

@section('content')
    <div class="container">
        <h2>Verify Your Account</h2>
        <form action="{{ route('verify.post') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="otp">OTP:</label>
                <input type="text" id="otp" name="otp" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Verify</button>

            @if (session('message'))
                <div class="alert alert-danger mt-2">
                {{ session('message') }}
                </div>
            @endif
        </form>
    </div>
@endsection
