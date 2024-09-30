@extends('layouts.app')

@section('title', 'Complaints')

@section('content')
    <div class="container">
        <h2>Your Complaints</h2>


        <ul class="list-group mt-4">
            @foreach($payload as $complaint)
                <li class="list-group-item">
                    <h4>{{ $complaint->title }}</h4>
                    <p>{{ $complaint->description }}</p>
                    <a href="{{ route('complaints.details', $complaint->id) }}">View Details</a>
                </li>
            @endforeach
        </ul>
    </div>
@endsection
