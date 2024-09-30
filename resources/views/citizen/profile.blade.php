@extends('layouts.app')

@section('title', 'Profile')

@section('content')
    <div class="container">
        <h2>{{ $userdata->name }}'s Profile</h2>
        <p><strong>Email:</strong> {{ $userdata->email }}</p>
        <p><strong>Account Status:</strong> {{ $userdata->accountstatus }}</p>
        <p><strong>Role:</strong> {{ $userdata->role }}</p>

        <h4>Your Complaints</h4>
        <ul class="list-group">
            @foreach($allComplaints as $complaint)
                <li class="list-group-item">
                    <h5>{{ $complaint->title }}</h5>
                    <p>{{ $complaint->description }}</p>
                    <a href="{{ route('complaints.details', $complaint->id) }}">View Details</a>
                </li>
            @endforeach
        </ul>
    </div>
@endsection
