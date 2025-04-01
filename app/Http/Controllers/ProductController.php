<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductOption;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('options')->get(); // Load options with products
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'required|image|max:2048',
            'options' => 'required|array',
            'options.*.option' => 'required|string',
            'options.*.price' => 'required|numeric',
            'options.*.quantity' => 'required|integer',
        ]);

        // Handle image upload
        $imagePath = $request->file('image')->store('public/images');

        // Create the product
        $product = Product::create([
            'name' => $validated['name'],
            'category_id' => $validated['category_id'],
            'image' => $imagePath,
        ]);

        // Save product options
        foreach ($request->options as $option) {
            $product->options()->create([
                'option' => $option['option'],
                'price' => $option['price'],
                'quantity' => $option['quantity'],
            ]);
        }

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|max:2048',
            'options' => 'required|array',
            'options.*.option' => 'required|string',
            'options.*.price' => 'required|numeric',
            'options.*.quantity' => 'required|integer',
        ]);

        // Update product data
        $product->update($request->only(['name', 'category_id']));

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('public/images');
            $product->image = $imagePath;
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

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }

    public function show(Product $product)
    {
        $product->load('options'); // Load options for the product
        return view('products.show', compact('product'));
    }

    public function showProducts(Request $request)
    {
        $categorySlug = $request->query('category');
        $category = Category::where('slug', $categorySlug)->first();
        $categories = Category::all();


        // TODO: PAGINATION IS NOT WORKING!
        $perPage = 100; // Number of products to load per scroll
        $page = $request->get('page', 1); // Get the current page number

        if ($category) {
            $products = Product::where('category_id', $category->id)->with('options')->paginate($perPage, ['*'], 'page', $page);
        } else {
            $products = Product::with('options')->paginate($perPage, ['*'], 'page', $page);
        }

        if ($request->ajax()) {
            return response()->json([
                'products' => $products->items(),
                'next_page_url' => $products->nextPageUrl()
            ]);
        }

        return view('product', compact('products', 'categories', 'categorySlug'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $products = Product::where('name', 'LIKE', "%$query%")->with('options')->get();
        $categories = Category::all();

        return view('product', compact('products', 'categories'))->with('categorySlug', null);
    }

    public function autocomplete(Request $request)
    {
        $query = $request->input('query');
        
        if (empty($query)) {
            return response()->json([]);
        }
        
        $products = Product::where('name', 'LIKE', $query.'%')
            ->orderBy('name')
            ->limit(10)
            ->pluck('name')
            ->toArray();
        
        return response()->json($products);
    }

    public function welcomeProducts(Request $request)
    {
        $products = Product::whereDoesntHave('category', function ($query) {
            $query->where('name', 'Clearance');
        })->with('options')->paginate(8);

        $clearanceProducts = Product::whereHas('category', function ($query) {
            $query->where('name', 'Clearance');
        })->with('options')->paginate(8);

        if ($request->ajax()) {
            return view('welcome', compact('products', 'clearanceProducts'));
        }

        return view('welcome', compact('products', 'clearanceProducts'));
    }

    public function getOptions($id)
    {
        $product = Product::with('options')->findOrFail($id); // Fetch product with options
        return response()->json([
            'image' => asset($product->image), // Full URL to the product image
            'name' => $product->name, // Product name
            'options' => $product->options->map(function ($option) {
                return [
                    'id' => $option->id,
                    'option' => $option->option,
                    'price' => $option->price,
                    'quantity' => $option->quantity,
                ];
            }),
        ]);
    }
}