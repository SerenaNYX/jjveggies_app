<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

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
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $product = new Product($request->all());
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $product->image = '/storage/' . $imagePath;
        }
        $product->save();

        return redirect()->route('staff.products.index')->with('success', 'Product added successfully');
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

        $product->update($request->all());
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $product->image = '/storage/' . $imagePath;
        }

        return redirect()->route('staff.products.index')->with('success', 'Product updated successfully');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('staff.products.index')->with('success', 'Product deleted successfully');
    }
}
