@extends('layouts.app')

@section('content')
    <h1>Edit Comment</h1>

    <form action="{{ route('comments.update', $comment->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="message" class="form-label">Message</label>
            <textarea name="message" id="message" class="form-control" required>{{ $comment->message }}</textarea>
        </div>

        <div class="mb-3">
            <label for="user_id" class="form-label">User</label>
            <select name="user_id" id="user_id" class="form-select" required>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ $comment->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="parent_id" class="form-label">Parent Comment (Optional)</label>
            <select name="parent_id" id="parent_id" class="form-select">
                <option value="">None</option>
                @foreach($comments as $parentComment)
                    <option value="{{ $parentComment->id }}" {{ $comment->parent_id == $parentComment->id ? 'selected' : '' }}>
                        {{ Str::limit($parentComment->message, 50) }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update Comment</button>
    </form>
@endsection
