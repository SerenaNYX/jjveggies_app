<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductOption;
use App\Models\OrderItem;
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

    // IS store function NEEDED in customer's ProductController??
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'required|image|max:2048',
            'options' => 'required|array',
            'options.*.option' => 'required|string',
            'options.*.price' => 'required|numeric',
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

    // IS update function NEEDED in customer's ProductController??
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|max:2048',
            'options' => 'required|array',
            'options.*.option' => 'required|string',
            'options.*.price' => 'required|numeric',
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

    // SHOW INDIVIDUAL PRODUCTS
    public function show(Product $product)
    {
        if ($product->status !== 'available') {
            abort(404);
        }
        
        $product->increment('views_count');
        $product->load('options');
        
        // Get recommended products in the specified order
        $frequentlyBoughtTogether = $this->getFrequentlyBoughtTogether($product);
        $sameCategoryProducts = $this->getSameCategoryProducts($product, 5 - $frequentlyBoughtTogether->count());
        
        // Merge and ensure no duplicates
        $recommendedProducts = $frequentlyBoughtTogether->merge($sameCategoryProducts)
            ->unique('id');
        
        // If still less than 5, fill with popular products excluding already recommended ones
        if ($recommendedProducts->count() < 5) {
            $needed = 5 - $recommendedProducts->count();
            $excludeIds = $recommendedProducts->pluck('id')->push($product->id);
            
            $additionalProducts = Product::whereNotIn('id', $excludeIds)
                ->with('options')
                ->withCount('orderItems')
                ->orderByDesc('order_items_count')
                ->orderByDesc('views_count')
                ->take($needed)
                ->get();
            
            $recommendedProducts = $recommendedProducts->merge($additionalProducts);
        }
        
        // Get popular products based on views and purchases (excluding current and recommended products)
        $excludeIds = $recommendedProducts->pluck('id')->push($product->id);
        $popularProducts = $this->getPopularProducts(5, $excludeIds);
        
        return view('products.show', compact('product', 'recommendedProducts', 'popularProducts'));
    }

    protected function getFrequentlyBoughtTogether(Product $product)
    {
        // Get order IDs that contain the current product
        $orderIds = OrderItem::where('product_id', $product->id)
            ->pluck('order_id')
            ->toArray();
        
        // Get products that appear in those orders (excluding current product)
        return Product::whereHas('orderItems', function($query) use ($orderIds) {
                $query->whereIn('order_id', $orderIds);
            })
            ->available()
            ->where('id', '!=', $product->id)
            ->with('options')
            ->withCount(['orderItems as co_occurrences' => function($query) use ($orderIds) {
                $query->whereIn('order_id', $orderIds);
            }])
            ->orderByDesc('co_occurrences')
            ->take(5)
            ->get();
    }

    protected function getSameCategoryProducts(Product $product, $limit = 5)
    {
        if ($limit <= 0) return collect();
        
        return Product::where('category_id', $product->category_id)
            ->available()
            ->where('id', '!=', $product->id)
            ->with('options')
            ->take($limit)
            ->get();
    }

    protected function getPopularProducts($limit = 5, $excludeIds = [])
    {
        $query = Product::query()
            ->available()
            ->with('options')
            ->withCount('orderItems')
            ->orderByDesc('order_items_count')
            ->orderByDesc('views_count')
            ->take($limit);

        if (!empty($excludeIds)) {
            $query->whereNotIn('id', $excludeIds);
        }

        $popular = $query->get();

        // If not enough, fallback to random products
        if ($popular->count() < $limit) {
            $remaining = $limit - $popular->count();
            $random = Product::query()
                ->available()
                ->with('options')
                ->whereNotIn('id', $popular->pluck('id')->merge($excludeIds))
                ->inRandomOrder()
                ->take($remaining)
                ->get();
            
            $popular = $popular->merge($random);
        }
        
        return $popular;
    }

    // SHOW IN PRODUCT PAGE
    public function showProducts(Request $request)
    {
        $categorySlug = $request->query('category');
        $category = Category::where('slug', $categorySlug)->first();
        $categories = Category::all();

        $query = Product::available()->with('options');

        if ($category) {
            $query->where('category_id', $category->id);
        }

        $products = $query->orderBy('name', 'asc')->get();

        return view('product', compact('products', 'categories', 'categorySlug'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $products = Product::where('name', 'LIKE', "%$query%")
            ->available()
            ->with('options')
            ->get();
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
            ->available()
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
        })
        ->available()
        ->with('options')
        ->withCount('orderItems') // Count of purchases
        ->orderByDesc('views_count') // Most viewed first
        ->orderByDesc('order_items_count') // Then most purchased
        ->paginate(8);


        $clearanceProducts = Product::whereHas('category', function ($query) {
                $query->where('name', 'Clearance');
            })
            ->available()
            ->with('options')
            ->paginate(8);

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