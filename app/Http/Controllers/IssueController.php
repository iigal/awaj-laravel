<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Issue;
use App\Models\Category;
use App\Models\User;
use Auth;
use Storage;

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
        return view('issues.show', compact('issue', 'comments'));
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

    public function listIssues(Request $request)
    {
        $searchText = $request->query('search_text');
        $authUser = Auth::id();
        if (isset($searchText) && !is_null($searchText)) {
            $issues = Issue::with(['categories', 'subcategories'])->whereLike("title", "%" . $searchText . "%")->orWhereLike("description", "%" . $searchText . "%")->get();
        } else {
            $issues = Issue::with(['categories', 'subcategories'])->get();

        }
        for ($i = 0; $i < count($issues); $i++) {
            if (!$issues[$i]->images || count($issues[$i]->images) < 1) {
                continue;
            }
            $imageUrls = collect($issues[$i]->images)->map(function ($imagePath) {
                // Assuming $imagePath is a relative path like 'uploads/example.jpg'
                return Storage::disk('public')->url($imagePath);
            });
            $issues[$i]->images = $imageUrls;
        }
        return response()->json([
            'success' => true,
            'data' => $issues
        ], Response::HTTP_OK);
    }

    public function listIssuesWithFilter(Request $request)
    {
        $searchText = $request->all('search_text');
        $statusFilter = $request->all('status_filters');

        if (isset($searchText) && !is_null($searchText)) {
            $issues = Issue::with(['categories', 'subcategories'])->whereLike('title', '%' . $searchText . '%')->orWhereLike('description', '%' . $searchText . '%')->whereIn('status', $statusFilter)->get();
        } else {
            $issues = Issue::with(['categories', 'subcategories'])->whereIn('status', [])->get();
        }
        for ($i = 0; $i < count($issues); $i++) {
            if (!$issues[$i]->images || count($issues[$i]->images) < 1) {
                continue;
            }
            $imageUrls = collect($issues[$i]->images)->map(function ($imagePath) {
                // Assuming $imagePath is a relative path like 'uploads/example.jpg'
                return Storage::disk('public')->url($imagePath);
            });
            $issues[$i]->images = $imageUrls;
        }
        return response()->json([
            'success' => true,
            'data' => $issues
        ], Response::HTTP_OK);
    }

    public function getIssueCount()
    {
        $authUser = Auth::id();
        $issues = Issue::where("user_id",$authUser)->get('id')->count();
        return response()->json([
            'success' => true,
            'data' => [array("count" => $issues)]
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
            'images' => 'nullable|'
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

    public function listIssueImage(string $issue_id)
    {

        $issue = Issue::find($issue_id);
        $images = $issue->images; // Assuming 'images' is a JSON field or an array of paths

        if (!$images) {
            return response()->json([
                'success' => false,
                'message' => 'No images found for this issue.',
                'data' => []
            ], Response::HTTP_OK);
        }

        // Generate URLs for each image
        $imageUrls = collect($images)->map(function ($imagePath) {
            // Assuming $imagePath is a relative path like 'uploads/example.jpg'
            return Storage::disk('public')->temporaryUrl($imagePath, now()->addMinutes(30)); // Signed URL
        });
        return response()->json([
            'success' => true,
            'data' => $imageUrls
        ], Response::HTTP_OK);
    }

    public function createIssueWithImage(Request $request)
    {

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'string|in:pending,inprogress,other,closed',
            'user_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'nullable|string'
            // 'sub_category_id' => 'exists:categories,id',
            // 'is_published' => 'boolean',
        ]);
        $uploadedFiles = [];
        $imageData = $validatedData["images"];
        unset($validatedData["images"]);
        if (!empty($imageData)) {
            foreach ($imageData as $key => $base64Image) {
                try {
                    // Match the MIME type from the Base64 string
                    if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)) {
                        $fileExtension = strtolower($type[1]); // Get the file extension (e.g., jpg, png)

                        // Validate supported file types
                        if (!in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'])) {
                            return response()->json([
                                'message' => "Unsupported file type at index {$key}: {$fileExtension}",
                            ], 400);
                        }

                        // Remove the data URI prefix to decode the Base64 string
                        $base64Image = substr($base64Image, strpos($base64Image, ',') + 1);
                    } else {
                        return response()->json([
                            'message' => "Invalid image format at index {$key}",
                        ], 400);
                    }

                    // Decode the Base64 string
                    $imageData = base64_decode($base64Image);

                    if ($imageData === false) {
                        return response()->json([
                            'message' => "Failed to decode image at index {$key}",
                        ], 400);
                    }

                    // Generate a unique name for the file
                    $uniqueFileName = uniqid() . "_image{$key}.{$fileExtension}";

                    // Save the file (e.g., to storage/app/uploads)
                    $filePath = "uploads/{$uniqueFileName}";
                    Storage::disk('public')->put($filePath, $imageData);
                    // Add the file URL to the response
                    $uploadedFiles[] = $filePath;
                } catch (\Exception $e) {
                    return response()->json([
                        'message' => "Failed to process image at index {$key}",
                        'error' => $e->getMessage(),
                    ], 400);
                }
            }
        }
        // Create issue in the database
        $issue = Issue::create($validatedData);
        $issue->images = $uploadedFiles;
        $issue->save();
        return response()->json([
            'success' => true,
            'message' => 'Issue created successfully.',
            'data' => $issue
        ], Response::HTTP_CREATED);
    }
}
