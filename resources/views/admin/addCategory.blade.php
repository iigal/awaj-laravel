@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Add Category</h2>

    <form action="{{ url('/admin/type') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="type">Category Name</label>
            <input type="text" name="type" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Add Category</button>
    </form>

    <h3 class="mt-5">Existing Categories</h3>
    <ul>
        @foreach($database as $category)
        <li>{{ $category->type }}</li>
        @endforeach
    </ul>
</div>
@endsection
