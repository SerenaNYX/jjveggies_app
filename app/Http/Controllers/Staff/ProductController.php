<?php

namespace App\Http\Controllers\Staff;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductOption;
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
            $products = Product::where('category_id', $categoryId)->with(['category', 'options'])->get();
        } else {
            $products = Product::with(['category', 'options'])->get();
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
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'options' => 'required|array',
            'options.*.option' => 'required|string',
            'options.*.price' => 'required|numeric',
            'options.*.quantity' => 'required|integer',
        ]);

        $product = new Product($request->only(['name', 'category_id', 'description']));

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->storeAs('img', $request->file('image')->getClientOriginalName(), 'public');
            $product->image = 'storage/' . $imagePath;
        }

        $product->save();

        // Save product options
        foreach ($request->options as $option) {
            $product->options()->create([
                'option' => $option['option'],
                'price' => $option['price'],
                'quantity' => $option['quantity'],
            ]);
        }

        // Redirecting based on the user role
        $role = Auth::user()->role;
        $route = $role === 'admin' ? 'admin.products.index' : 'staff.products.index';
        return redirect()->route($route)->with('success', 'Product added successfully');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $product->load('options'); // Load options for the product
        return view('staff.edit-product', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'options' => 'required|array',
            'options.*.option' => 'required|string',
            'options.*.price' => 'required|numeric',
            'options.*.quantity' => 'required|integer',
        ]);

        // Update product data
        $product->update($request->only(['name', 'category_id', 'description']));

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->storeAs('img', $request->file('image')->getClientOriginalName(), 'public');
            $product->image = 'storage/' . $imagePath;
        }

        $product->save();

        // Update product options
        $product->options()->delete(); // Remove existing options
        foreach ($request->options as $option) {
            $product->options()->create([
                'option' => $option['option'],
                'price' => $option['price'],
                'quantity' => $option['quantity'],
            ]);
        }

        // Redirecting based on the user role
        $role = Auth::user()->role;
        $route = $role === 'admin' ? 'admin.products.index' : 'staff.products.index';
        return redirect()->route($route)->with('success', 'Product updated successfully');
    }

    public function deleteOption(ProductOption $option)
{
    $option->delete();
    return response()->json(['success' => true]);
}

    public function destroy(Product $product)
    {
        $product->delete();

        $role = Auth::user()->role;
        $route = $role === 'admin' ? 'admin.products.index' : 'staff.products.index';
        return redirect()->route($route)->with('success', 'Product deleted successfully');
    }
}