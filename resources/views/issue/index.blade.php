<!-- resources/views/complaints/index.blade.php -->

@extends('layouts.app')

@section('content')
    <h1>Complaints</h1>
    <ul>
        @foreach($complaints as $complaint)
            <li>
                <a href="{{ route('complaints.show', $complaint->id) }}">{{ $complaint->title }}</a>
                <p>Status: {{ $complaint->status }}</p>
            </li>
        @endforeach
    </ul>
@endsection
