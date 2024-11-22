@extends('layouts.app')

@section('content')
<h1>Add Comment to Issue: {{ $issue->title }}</h1>

<form action="{{ route('comments.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="message" class="form-label">Message</label>
        <textarea name="message" id="message" class="form-control" required></textarea>
    </div>

    <div class="mb-3">
        <label for="user_id" class="form-label">User</label>
        <select name="user_id" id="user_id" class="form-select" required>
            @foreach($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>
    </div>    
    @if (!$has_parent)
        <div class="mb-3">
            <label for="parent_id" class="form-label">Parent Comment (Optional)</label>
            <select name="parent_id" id="parent_id" class="form-select">
                <option value="">None</option>
                @foreach($comments as $parentComment)
                    <option value="{{ $parentComment->id }}">{{ Str::limit($parentComment->message, 50) }}</option>
                @endforeach
            </select>
        </div>
    @else
        <div class="mb-3">
            <label for="parent_id" class="form-label">Parent Comment</label>
            <select name="parent_id" id="parent_id" class="form-select">
                <option value="{{ $comments->id }}" selected>{{ Str::limit($comments->message, 50) }}</option>
            </select>
        </div>
    @endif



    <input type="hidden" name="issue_id" value="{{ $issue->id }}">

    <button type="submit" class="btn btn-primary">Add Comment</button>
</form>
@endsection