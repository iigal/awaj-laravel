@extends('layouts.app')

@section('content')
    <h1>{{ $issue->title }}</h1>

    <p>{{ $issue->description }}</p>

    <p>Status: {{ $issue->status }}</p>
    <p>Published: {{ $issue->is_published ? 'Yes' : 'No' }}</p>
    <p>User: {{ $issue->user->name }}</p>

    <h4>Categories</h4>
    <ul>
        @foreach($issue->categories as $category)
            <li>{{ $category->title }}</li>
        @endforeach
    </ul>

    <a href="{{ route('issues.edit', $issue->id) }}" class="btn btn-warning">Edit</a>
    <form action="{{ route('issues.destroy', $issue->id) }}" method="POST" style="display:inline-block;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Delete</button>
    </form>

    <a href="{{ route('issues.index') }}" class="btn btn-secondary">Back to Issues</a>
@endsection
