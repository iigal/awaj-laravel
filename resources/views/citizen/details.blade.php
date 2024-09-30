@extends('layouts.app')

@section('title', 'Complaint Details')

@section('content')
    <div class="container">
        <h2>Complaint Details</h2>
        <p><strong>Title:</strong> {{ $payload->title }}</p>
        <p><strong>Description:</strong> {{ $payload->description }}</p>

        @if ($payload->images)
            <h4>Images:</h4>
            @foreach ($payload->images as $image)
                <img src="{{ $image }}" alt="Complaint Image" style="max-width: 100%;">
            @endforeach
        @endif

        <h4>Comments</h4>
        <ul class="list-group">
            @foreach($allComment as $comment)
                @if(is_null($comment->parent_id))
                    <li class="list-group-item">
                        <strong>{{ $comment->user->name }}:</strong> {{ $comment->message }}
                        <form action="{{ route('comments.reply', [$payload->id, $comment->id, $comment->user_id]) }}" method="POST">
                            @csrf
                            <input type="text" name="reply" placeholder="Reply to comment" class="form-control">
                            <button type="submit" class="btn btn-primary mt-2">Reply</button>
                        </form>

                    @if ($comment->reply)
                        <ul>
                            @foreach ($comment->reply as $reply)
                                <li><strong>{{ $reply->user['name'] }}:</strong> {{ $reply['message'] }}</li>
                            @endforeach
                        </ul>
                    @endif
                    </li>
                @endif
            @endforeach
        </ul>

        <form action="{{ route('comments.add', $payload->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="comment">Add a comment:</label>
                <input type="text" id="comment" name="comment" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
@endsection
