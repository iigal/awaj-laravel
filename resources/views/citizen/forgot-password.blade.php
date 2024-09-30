@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
    <div class="container">
        <h2>Forgot Password</h2>
        <form action="{{ route('password.forgot.post') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>

            @if ($message)
                <div class="alert alert-danger mt-2">
                    {{ $message }}
                </div>
            @endif
        </form>
    </div>
@endsection
