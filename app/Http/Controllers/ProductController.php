<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }
    
/*
    public function index(Request $request)
    {
        // Get all products (eager load the category)
        $products = Product::with('category')->get();

        // Fetch products in the "Clearance" category
        $clearanceProducts = Product::with('category')
            ->whereHas('category', function ($query) {
                $query->where('name', 'Clearance'); // Make sure the category is 'Clearance'
            })
            ->get();

        // Pass both the featured and clearance products to the view
        return view('welcome', compact('products', 'clearanceProducts'));
    }*/


    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'image' => 'required|image|max:2048',
        ]);

        $imagePath = $request->file('image')->store('public/images');

        Product::create([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'image' => $imagePath,
        ]);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('public/images');
            $product->update([
                'name' => $validated['name'],
                'price' => $validated['price'],
                'image' => $imagePath,
            ]);
        } else {
            $product->update([
                'name' => $validated['name'],
                'price' => $validated['price'],
            ]);
        }

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }

    public function showProducts(Request $request)
    {
        $categorySlug = $request->query('category'); // Retrieve the category from the query parameters
        $category = Category::where('slug', $categorySlug)->first();
        $categories = Category::all(); // Retrieve all categories

        if ($category) {
            // Filter products by the selected category
            $products = Product::where('category_id', $category->id)->get();
        } else {
            // Include all products, including those without a category
            $products = Product::all();
        }

        // Pass the variables to the view
        return view('product', compact('products', 'categories', 'categorySlug'));
    }

    public function search(Request $request)
{
    $query = $request->input('query');
    $products = Product::where('name', 'LIKE', "%$query%")->get();
    $categories = Category::all();

    return view('product', compact('products', 'categories'))->with('categorySlug', null);
}


    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function welcomeProducts()
    {
        $products = Product::all();
        return view('welcome', compact('products'));
    }
}