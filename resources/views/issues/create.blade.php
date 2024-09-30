@extends('layouts.app')

@section('content')
    <h1>Create Issue</h1>

    <form action="{{ route('issues.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" name="title" id="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label for="user_id" class="form-label">User</label> 
            <select name="user_id" id="user_id" class="form-select" required> 
                @foreach($users as $user) 
                    <option value="{{ $user->id }}">{{ $user->name }}</option> 
                @endforeach 
            </select> 
        </div>

        <div class="mb-3">
            <label for="categories" class="form-label">Categories</label>
            <select name="categories[]" id="categories" class="form-select" multiple required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->title }}</option>
                @endforeach
            </select>
        </div>
    
        <div class="mb-3">
            <label for="is_published" class="form-label">Is Published?</label>
            <select name="is_published" id="is_published" class="form-select">
                <option value="0">No</option>
                <option value="1">Yes</option>
            </select>
        </div>
    
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
@endsection