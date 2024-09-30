{{-- resources/views/categories/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Category Details')

@section('content')
    <h1>Category Details</h1>

    <div class="mb-3">
        <label class="form-label">Title</label>
        <input type="text" class="form-control" value="{{ $category->title }}" disabled>
    </div>

    <div class="mb-3">
        <label class="form-label">Parent ID</label>
        <input type="text" class="form-control" value="{{ $category->parent_id }}" disabled>
    </div>

    <a href="{{ route('categories.index') }}" class="btn btn-secondary">Back</a>
@endsection
