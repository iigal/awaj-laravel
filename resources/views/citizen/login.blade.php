@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="container">
        <h2>Login</h2>
        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
            <div class="alert alert-danger mt-2">
                {{ session('message') }}
            </div>

        </form>
    </div>
    <p class="small-xl pt-3 text-center">
            <span class="text-muted">Not a member?</span>
            <a href="{{ route('auth.signup') }}">Sign Up</a>
        </p>
@endsection
