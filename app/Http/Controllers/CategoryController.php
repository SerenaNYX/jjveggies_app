<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    // Display the list of categories
    public function index()
    {
        $categories = Category::all();
        return view('categories.manage-category', compact('categories'));
    }

    // Show the form for creating a new category
    public function create()
    {
        return view('categories.create-category');
    }

    // Store a newly created category in the database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories,name|max:255',
        ]);

        $category = new Category();
        $category->name = $request->input('name');
        $category->slug = Str::slug($request->input('name'));
        $category->save();

        return redirect()->route(Auth::guard('employee')->user()->role . '.categories.index')->with('success', 'Category created successfully.');
    }

    // Show the form for editing an existing category
    public function edit(Category $category)
    {
        return view('categories.create-category', compact('category'));
    }

    // Update an existing category in the database
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|unique:categories,name,' . $category->id . '|max:255',
        ]);

        $category->name = $request->input('name');
        $category->slug = Str::slug($request->input('name'));
        $category->save();

        return redirect()->route(Auth::guard('employee')->user()->role . '.categories.index')->with('success', 'Category updated successfully.');
    }

    // Delete a category from the database
    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route(Auth::guard('employee')->user()->role . '.categories.index')->with('success', 'Category deleted successfully.');
    }
}
