@extends('layouts.app')

@section('content')
    <h1>Edit Category: {{ $category->title }}</h1>

    <form action="{{ route('categories.update', $category->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ $category->title }}" required>
        </div>

        <div class="mb-3">
            <label for="parent_id" class="form-label">Parent Category (Optional)</label>
            <select name="parent_id" id="parent_id" class="form-select">
                <option value="">None</option>
                @foreach($categories as $parentCategory)
                    <option value="{{ $parentCategory->id }}" 
                        @if($category->parent_id == $parentCategory->id) selected @endif>
                        {{ $parentCategory->title }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
@endsection
