@extends('layouts.app')

@section('content')
    <h1>Issues</h1>

    <a href="{{ route('issues.create') }}" class="btn btn-primary mb-3">Create Issue</a>

    <table class="table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Status</th>
                <th>User</th>
                <th>Categories</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($issues as $issue)
                <tr>
                    <td>{{ $issue->title }}</td>
                    <td>{{ Str::limit($issue->description, 50) }}</td>
                    <td>{{ $issue->status }}</td>
                    <td>{{ $issue->user->name }}</td>
                    <td>
                        @foreach($issue->categories as $category)
                            <span class="badge bg-secondary">{{ $category->title }}</span>
                        @endforeach
                    </td>
                    <td>
                        <a href="{{ route('issues.show', $issue->id) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('issues.edit', $issue->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('issues.destroy', $issue->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
