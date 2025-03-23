@extends('layouts.employee')

@section('content')
<div class="container">
    <h1 class="text-center">Manage Products</h1>

    <!-- Search Bar and Category Filter -->
    <div class="flex-container mb-4">
        <!-- Search Form -->
        <input type="text" id="searchQuery" class="form-control" placeholder="Search for products..." onkeyup="searchProducts()">
        <!-- Category Filter -->
        <select id="category-filter" class="form-control-category" onchange="filterByCategory()">
            <option value="">All Categories</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        <a href="{{ route(Auth::guard('employee')->user()->role . '.products.create') }}" class="button-add">Add New Product</a>
        <a href="{{ route(Auth::guard('employee')->user()->role . '.categories.index') }}" class="button-add">Manage Categories</a>
    </div>

    <!-- Product Table -->
    <table class="table table-striped table-product" id="productTable">
        <thead>
            <tr>
                <th class="number-column">#</th>
                <th class="image-column">Image</th>
                <th class="name-column">Name</th>
                <th class="category-column">Category</th>
                <th class="options-column">Options</th> <!-- New column for options -->
                <th class="actions-column">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $index => $product)
            <tr>
                <td class="number-column">{{ $index + 1 }}</td>
                <td class="image-column"><img src="{{ asset($product->image) }}" alt="{{ $product->name }}" height="50"></td>
                <td class="name-column">{{ $product->name }}</td>
                <td class="category-column">{{ $product->category->name ?? 'Uncategorized' }}</td>
                <td class="options-column">
                    @foreach ($product->options as $option)
                        <div>
                            <strong>{{ $option->option }}</strong>:
                            RM{{ number_format($option->price, 2) }} ({{ $option->quantity }} in stock)
                        </div>
                    @endforeach
                </td>
                <td class="actions-column">
                    <a href="{{ route(Auth::guard('employee')->user()->role . '.products.edit', $product->id) }}" class="button-edit">Edit</a>
                    <form action="{{ route(Auth::guard('employee')->user()->role . '.products.destroy', $product->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="button button-danger" onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
// Function to search products
function searchProducts() {
    var query = document.getElementById('searchQuery').value.toLowerCase();

    // Filter products table
    var productRows = document.getElementById('productTable').getElementsByTagName('tr');
    for (var i = 1; i < productRows.length; i++) {
        var name = productRows[i].getElementsByTagName('td')[2].textContent.toLowerCase();
        var category = productRows[i].getElementsByTagName('td')[3].textContent.toLowerCase();
        
        if (name.includes(query) || category.includes(query)) {
            productRows[i].style.display = '';
        } else {
            productRows[i].style.display = 'none';
        }
    }
}

// Function to filter by category
function filterByCategory() {
    const selectedCategory = document.getElementById('category-filter').value;
    const searchQuery = document.getElementById('searchQuery').value;

    // Update URL with search and category parameters
    let url = `{{ route(Auth::guard('employee')->user()->role . '.products.index') }}`;

    // Add the category filter if selected
    if (selectedCategory) {
        url += `?category=${selectedCategory}`;
    }

    // Add the search query if it exists
    if (searchQuery) {
        // If the category is already in the URL, append the search parameter; otherwise, create it
        if (url.includes('?')) {
            url += `&search=${searchQuery}`;
        } else {
            url += `?search=${searchQuery}`;
        }
    }

    // Redirect to the filtered page
    window.location.href = url;
}
</script>

@endsection