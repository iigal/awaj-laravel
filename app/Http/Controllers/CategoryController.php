<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    // Display a listing of the categories
    public function index()
    {
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }

    // Show the form for creating a new category
    public function create()
    {
        // Fetch all categories to show as options for parent
        $categories = Category::all();
        return view('categories.create', compact('categories'));
    }

    // Store a newly created category in storage
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        Category::create($validatedData);
        return redirect()->route('categories.index');
    }

    // Display the specified category
    public function show(Category $category)
    {
        return view('categories.show', compact('category'));
    }

    // Show the form for editing the specified category
    public function edit(Category $category)
    {
        // Fetch all categories except the current one to avoid circular references
        $categories = Category::where('id', '!=', $category->id)->get();
        return view('categories.edit', compact('category', 'categories'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'title' => 'required',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        $category->update($request->all());
        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }

    //API REQUESTS

    // API Methods

    // Display a listing of the categories (API)
    public function apiIndex(Request $request)
    {
        $includeSubcategories = $request->query('include_subcategories', "false");
        // Query the top-level categories (those without a parent)
        $categories = Category::whereNull('parent_id')
            ->with(strtolower($includeSubcategories) == "true" ? 'children' : []) // Eager load children if requested
            ->get();
        return response()->json([
            'success' => true,
            'data' => $categories
        ], Response::HTTP_OK);
    }

    // Store a newly created category (API)
    public function apiStore(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        $category = Category::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully.',
            'data' => $category
        ], Response::HTTP_CREATED);
    }

    // Show a category (API)
    public function apiShow(Category $category)
    {
        return response()->json([
            'success' => true,
            'data' => $category
        ], Response::HTTP_OK);
    }

    // Update a category (API)
    public function apiUpdate(Request $request, Category $category)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        $category->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully.',
            'data' => $category
        ], Response::HTTP_OK);
    }

    // Delete a category (API)
    public function apiDestroy(Category $category)
    {
        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully.',
        ], Response::HTTP_OK);
    }
}
