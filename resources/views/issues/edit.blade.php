
#### `resources/views/issues/edit.blade.php`

This view is for editing an existing issue.

```php
@extends('layouts.app')

@section('content')
    <h1>Edit Issue: {{ $issue->title }}</h1>

    <form action="{{ route('issues.update', $issue->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ $issue->title }}" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control" required>{{ $issue->description }}</textarea>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-select" required>
                <option value="queue" {{ $issue->status == 'queue' ? 'selected' : '' }}>Queue</option>
                <option value="progress" {{ $issue->status == 'progress' ? 'selected' : '' }}>Progress</option>
                <option value="success" {{ $issue->status == 'success' ? 'selected' : '' }}>Success</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="user_id" class="form-label">User</label>
            <select name="user_id" id="user_id" class="form-select" required>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ $issue->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="categories" class="form-label">Categories</label>
            <select name="categories[]" id="categories" class="form-select" multiple required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ in_array($category->id, $issue->categories->pluck('id')->toArray()) ? 'selected' : '' }}>
                        {{ $category->title }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="is_published" class="form-label">Is Published?</label>
            <select name="is_published" id="is_published" class="form-select">
                <option value="0" {{ !$issue->is_published ? 'selected' : '' }}>No</option>
                <option value="1" {{ $issue->is_published ? 'selected' : '' }}>Yes</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
@endsection
