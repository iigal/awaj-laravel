<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Issue;
use Auth;

class CommentController extends Controller
{
    public function addComment(Request $request, $issueId)
    {
        $request->validate(['comment' => 'required']);

        $comment = new Comment();
        $comment->message = $request->comment;
        $comment->user_id = Auth::id();
        $comment->issue_id = $issueId;
        $comment->save();

        return redirect()->route('issues.details', $issueId);
    }

    public function reply(Request $request, $issueId, $commentId)
    {
        $request->validate(['reply' => 'required']);

        $comment = Comment::findOrFail($commentId);
        $comment->reply()->create([
            'message' => $request->reply,
            'issue_id' => $issueId,
            'parent_id' => $commentId,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('issues.details', $issueId);
    }

    // Display a listing of the comments for a specific issue
    public function index(Issue $issue)
    {
        $comments = Comment::where('issue_id', $issue->id)->with('user', 'parent')->get();
        return view('comments.index', compact('comments', 'issue'));
    }

    // Show the form for creating a new comment
    public function create(Issue $issue)
    {
        $users = User::all();
        $comments = Comment::where('issue_id', $issue->id)->get();
        return view('comments.create', compact('issue', 'users', 'comments'));
    }

    // Store a newly created comment in storage
    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'parent_id' => 'nullable|exists:comments,id',
            'issue_id' => 'required|exists:issues,id',
        ]);

        Comment::create($request->all());
        return redirect()->route('comments.index', $request->issue_id)->with('success', 'Comment added successfully.');
    }

    // Show the form for editing the specified comment
    public function edit(Comment $comment)
    {
        $users = User::all();
        $comments = Comment::where('issue_id', $comment->issue_id)->get();
        return view('comments.edit', compact('comment', 'users', 'comments'));
    }

    // Update the specified comment in storage
    public function update(Request $request, Comment $comment)
    {
        $request->validate([
            'message' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $comment->update($request->all());
        return redirect()->route('comments.index', $comment->issue_id)->with('success', 'Comment updated successfully.');
    }

    // Remove the specified comment from storage
    public function destroy(Comment $comment)
    {
        $issueId = $comment->issue_id; // Store the issue id to redirect later
        $comment->delete();
        return redirect()->route('comments.index', $issueId)->with('success', 'Comment deleted successfully.');
    }


}
