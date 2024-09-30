@extends('layouts.app')

@section('content')
    <h1>Create Category</h1>

    <form action="{{ route('categories.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" name="title" id="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="parent_id" class="form-label">Parent Category (Optional)</label>
            <select name="parent_id" id="parent_id" class="form-select">
                <option value="">None</option>
                @foreach($categories as $parentCategory)
                    <option value="{{ $parentCategory->id }}">{{ $parentCategory->title }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Create</button>
    </form>
@endsection
