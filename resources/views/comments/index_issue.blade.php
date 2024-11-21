<h1>Comments for Issue: {{ $issue->title }}</h1>

<a href="{{ route('comments.create', ['issue' => $issue]) }}" class="btn btn-primary mb-3">Add Comment</a>

<table class="table">
    <thead>
        <tr>
            <th>Message</th>
            <th>User</th>
            <th>Parent Comment</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($comments as $comment)
            <tr>
                <td>{{ $comment->message }}</td>
                <td>{{ $comment->user->name }}</td>
                <td>{{ $comment->parent ? $comment->parent->message : 'None' }}</td>
                <td>
                    <a href="{{ route('comments.edit', $comment->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('comments.destroy', $comment->id) }}" method="POST"
                        style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>