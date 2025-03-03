<?php

namespace App\Http\Controllers\Staff;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categoryId = $request->query('category');
        $categories = Category::all(); // Retrieve all categories

        if ($categoryId) {
            $products = Product::where('category_id', $categoryId)->with('category')->get();
        } else {
            $products = Product::with('category')->get();
        }

        return view('staff.manage-products', compact('products', 'categories')); // Pass categories to the view
    }

    public function create()
    {
        $categories = Category::all();
        return view('staff.create-product', compact('categories'));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'price' => 'required|numeric',
        'category_id' => 'nullable|exists:categories,id',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    $product = new Product($request->all());

    // Handle image upload
    if ($request->hasFile('image')) {
        // Store the image in the public/img folder
        $imagePath = $request->file('image')->storeAs('img', $request->file('image')->getClientOriginalName(), 'public');
        $product->image = 'storage/' . $imagePath; // Save relative path to the DB
    }

    $product->save();

    // Redirecting based on the user role
    $role = Auth::user()->role;
    $route = $role === 'admin' ? 'admin.products.index' : 'staff.products.index';
    return redirect()->route($route)->with('success', 'Product added successfully');
}




    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('staff.edit-product', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'price' => 'required|numeric',
        'category_id' => 'nullable|exists:categories,id',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    // Update product data
    $product->update($request->except('image')); // Keep the previous image if not updating

    if ($request->hasFile('image')) {
        // Store the new image in the public/img folder
        $imagePath = $request->file('image')->storeAs('img', $request->file('image')->getClientOriginalName(), 'public');
        $product->image = 'storage/' . $imagePath; // Save relative path to the DB
    }

    $product->save();

    // Redirecting based on the user role
    $role = Auth::user()->role;
    $route = $role === 'admin' ? 'admin.products.index' : 'staff.products.index';
    return redirect()->route($route)->with('success', 'Product updated successfully');
}


    public function destroy(Product $product)
    {
        $product->delete();

        $role = Auth::user()->role;
        $route = $role === 'admin' ? 'admin.products.index' : 'staff.products.index';
        return redirect()->route($route)->with('success', 'Product deleted successfully');
    }
}
