<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Issue;
use App\Models\Category;
use App\Models\User;
use Auth;

class IssueController extends Controller
{
    public function list(Request $request)
    {
        $status = $request->input('status');
        $area = $request->input('area');

        $Issues = Issue::query();

        if ($status) {
            $Issues->where('status', $status);
        }

        if ($area) {
            $Issues->where('area', $area);
        }

        $payload = $Issues->get();
        $areas = Area::all();

        return view('citizen.Issue', compact('payload', 'areas'));
    }

    public function details($id)
    {
        $Issue = Issue::with('comments')->findOrFail($id);
        return view('citizen.details', ['payload' => $Issue, 'allComment' => $Issue->comments]);
    }

    public function creates(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        $Issue = new Issue();
        $Issue->title = $request->title;
        $Issue->description = $request->description;
        $Issue->area = $request->area;
        $Issue->user_id = Auth::id();
        $Issue->save();

        return redirect()->route('Issues')->with('message', 'Issue submitted successfully');
    }

    public function getIssuesByUser()
    {
        $Issues = Issue::where('user_id', Auth::id())->get();
        return view('citizen.profile', ['allIssues' => $Issues, 'userdata' => Auth::user()]);
    }

    // Display a listing of the resource
    public function index()
    {
        $issues = Issue::with('user', 'categories')->get();
        return view('issues.index', compact('issues'));
    }

    // Show the form for creating a new resource
    public function create()
    {
        $users = User::all();
        $categories = Category::all();
        return view('issues.create', compact('users', 'categories'));
    }

    // Store a newly created resource in storage
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|string|in:open,closed',
            'user_id' => 'required|exists:users,id',
            'is_published' => 'boolean',
        ]);

        $issue = Issue::create($request->all());
        // $issue->categories()->sync($request->categories);
        return redirect()->route('issues.index')->with('success', 'Issue created successfully.');
    }

    // Display the specified resource
    public function show(Issue $issue)
    {
        $issue->load('user', 'categories');
        $comments = Comment::where('issue_id', $issue->id)->with('user', 'parent')->get();
        return view('issues.show', compact('issue','comments'));
    }

    // Show the form for editing the specified resource
    public function edit(Issue $issue)
    {
        $users = User::all();
        $categories = Category::all();
        return view('issues.edit', compact('issue', 'users', 'categories'));
    }

    // Update the specified resource in storage
    public function update(Request $request, Issue $issue)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|string|in:open,closed',
            'user_id' => 'required|exists:users,id',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'is_published' => 'boolean',
        ]);

        $issue->update($request->all());
        $issue->categories()->sync($request->categories);
        return redirect()->route('issues.index')->with('success', 'Issue updated successfully.');
    }

    // Remove the specified resource from storage
    public function destroy(Issue $issue)
    {
        $issue->delete();
        return redirect()->route('issues.index')->with('success', 'Issue deleted successfully.');
    }

    // Mobile Api

    public function listIssues()
    {
        $issues = Issue::with(['categories', 'subcategories'])->get();
        return response()->json([
            'success' => true,
            'data' => $issues
        ], Response::HTTP_OK);
    }

    public function createIssue(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'string|in:pending,inprogress,other,closed',
            'user_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            // 'sub_category_id' => 'exists:categories,id',
            // 'is_published' => 'boolean',
        ]);

        $issue = Issue::create($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Issue created successfully.',
            'data' => $issue
        ], Response::HTTP_CREATED);

    }
}
