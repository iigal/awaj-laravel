@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Complaint Details</h2>

    <form action="{{ url('/admin/details/' . $payload->_id) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" class="form-control" value="{{ $payload->title }}" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" class="form-control" required>{{ $payload->description }}</textarea>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" class="form-control" required>
                <option value="Queue" {{ $payload->status == 'Queue' ? 'selected' : '' }}>Queue</option>
                <option value="Progress" {{ $payload->status == 'Progress' ? 'selected' : '' }}>In Progress</option>
                <option value="Success" {{ $payload->status == 'Success' ? 'selected' : '' }}>Success</option>
            </select>
        </div>

        <div class="form-group">
            <label for="area">Area</label>
            <select name="area" class="form-control">
                @foreach($database as $category)
                <option value="{{ $category->type }}" {{ $payload->area == $category->type ? 'selected' : '' }}>{{ $category->type }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="progressmessege">Progress Message</label>
            <input type="text" name="progressmessege" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="progressbar">Progress Bar</label>
            <input type="text" name="progressbar" class="form-control" value="{{ $payload->statusbar }}">
        </div>

        <button type="submit" class="btn btn-primary mt-3">Update</button>
    </form>

    <h3 class="mt-5">Comments</h3>
    @foreach($allComment as $comment)
    <div class="card mt-2">
        <div class="card-body">
            <h5>{{ $comment->userid->name }}</h5>
            <p>{{ $comment->message }}</p>

            <form action="{{ url('/' . $payload->_id . '/' . $comment->_id . '/' . $userid . '/replycomment') }}" method="POST">
                @csrf
                <div class="form-group">
                    <input type="text" name="reply" class="form-control" placeholder="Reply...">
                </div>
                <button type="submit" class="btn btn-secondary">Reply</button>
            </form>
        </div>
    </div>
    @endforeach
</div>
@endsection
