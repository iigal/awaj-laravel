<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Comment;
use App\Models\Issue;
use App\Models\User;
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

    public function showReply(Issue $issue, Comment $comments)
    {
        $users = User::all();
        $has_parent = true;
        return view('comments.create', compact('comments', 'issue', 'users', 'has_parent'));
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
        // $issue = Issue::findOrFail($id);
        $comments = Comment::where('issue_id', $issue->id)->with('user', 'parent')->get();
        return view('comments.index', compact('comments', 'issue'));
    }

    // Show the form for creating a new comment
    public function create(Request $request)
    {
        $has_parent = false;
        $users = User::all();
        $issue = Issue::findOrFail($request->issue);
        $comments = Comment::where('issue_id', $request->issue)->get();
        return view('comments.create', compact('issue', 'users', 'comments', 'has_parent'));
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
        return redirect()->route('comments.index', $request->issue)->with('success', 'Comment added successfully.');
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

    // Mobile Api
    public function getCommentsByIssueId(Request $request)
    {
        $comments = Comment::where('issue_id', $request->issue_id)
            ->whereNull('parent_id') // Get only root comments
            ->with('reply') // Load replies for each comment
            ->get();
        return response()->json([
            'success' => true,
            'data' => $comments
        ], Response::HTTP_OK);
    }
    public function addCommentByIssueId(Request $request)
    {
        $validatedData = $request->validate([
            'message' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'parent_id' => 'nullable|exists:comments,id',
            'issue_id' => 'required|exists:issues,id',
        ]);
        Comment::create($validatedData);
        return response()->json([
            'success' => true,
        ], Response::HTTP_OK);
    }
}
