@extends('layouts.employee')

@section('content')
<div class="container">
    <div class="manage-product">
    <h1 class="text-center">Manage Products</h1>

    <!-- Search Bar and Category Filter -->
    <div class="flex-container">
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

        <a href="{{ route(Auth::guard('employee')->user()->role . '.products.create') }}" class="button-add">New Product</a>
        <a href="{{ route(Auth::guard('employee')->user()->role . '.categories.index') }}" class="button-add">Manage Categories</a>
    </div>

    <!-- Product Table -->
    <table class="clean-table" id="productTable">
    <!--<table class="table table-striped table-product" id="productTable">-->
        <thead>
            <tr>
                <th class="number-column">Code</th>
                <th class="image-column">Image</th>
                <th class="name-column">Name</th>
                <th class="category-column">Category</th>
                <th class="options-column">Options</th>
                <th class="">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $index => $product)
            <tr class="hover-row {{ $product->status === 'unavailable' ? 'unavailable-product' : '' }}">
                <td class="number-column" onclick="window.location='{{ route(Auth::guard('employee')->user()->role . '.products.edit', $product->id) }}'" style="cursor: pointer;">{{ $product->product_number }}</td>
                <td class="image-column" onclick="window.location='{{ route(Auth::guard('employee')->user()->role . '.products.edit', $product->id) }}'" style="cursor: pointer;"><img src="{{ asset($product->image) }}" alt="{{ $product->name }}" height="50"></td>
                <td class="name-column" onclick="window.location='{{ route(Auth::guard('employee')->user()->role . '.products.edit', $product->id) }}'" style="cursor: pointer;">{{ $product->name }}</td>
                <td class="category-column" onclick="window.location='{{ route(Auth::guard('employee')->user()->role . '.products.edit', $product->id) }}'" style="cursor: pointer;">{{ $product->category->name ?? 'Uncategorized' }}</td>
                <td class="options-column" onclick="window.location='{{ route(Auth::guard('employee')->user()->role . '.products.edit', $product->id) }}'" style="cursor: pointer;">
                    @foreach ($product->options as $option)
                        <div>
                            <strong>{{ $option->option }}</strong>:
                            RM{{ number_format($option->price, 2) }} <!--({{ $option->quantity }} in stock)-->
                        </div>
                    @endforeach
                </td>
                <td class="actions-column">
                    <form action="{{ route(Auth::guard('employee')->user()->role . '.products.toggle-status', $product->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="button {{ $product->status === 'available' ? 'button-danger2' : 'button-danger3' }}">
                            {{ $product->status === 'available' ? 'Make Unavailable' : 'Make Available' }}
                        </button>
                    </form>
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
</div>

<script>
// Function to search products
function searchProducts() {
    var query = document.getElementById('searchQuery').value.toLowerCase();

    // Filter products table
    var productRows = document.getElementById('productTable').getElementsByTagName('tr');
    for (var i = 1; i < productRows.length; i++) {
        var product_number = productRows[i].getElementsByTagName('td')[0].textContent.toLowerCase();
        var name = productRows[i].getElementsByTagName('td')[2].textContent.toLowerCase();
        var category = productRows[i].getElementsByTagName('td')[3].textContent.toLowerCase();
        
        if (product_number.includes(query) || name.includes(query) || category.includes(query)) {
            productRows[i].style.display = '';
        } else {
            productRows[i].style.display = 'none';
        }
    }
}

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

<style>

    img {
        object-fit: scale-down;
    }
    .unavailable-product {
        background-color: #e6e6e6 !important;

        &:hover {
            background-color: #d2d2d2 !important;
        }
    }

    .clean-table {
        table-layout: fixed; 
        border-collapse: collapse; 
    }

    .actions-column {
        height: 100%;
        padding: 10px 15px;
        vertical-align: middle;
        box-sizing: border-box;
        .button {
            margin-bottom: 5px;
        }
    }

    .actions-column form:last-child {
        margin-bottom: 0;
    }

@media (max-width: 1500px) {

    .clean-table {
        font-size: 15px;
    }

    .actions-column .button {
        margin-bottom: 5px;
        padding: 0;
        font-size: 13px;
        width: 90px;
    }

    .button-add {
        font-size: 15px !important;
        line-height: normal;
    } 
}

@media (max-width: 1000px) {
    .actions-column .button {
        font-size: 12px;
        width: 80px;
    }

    .button-add {
        font-size: 14px !important;
    } 
}

@media (max-width: 900px) {
    .actions-column .button {
        width: 70px;
    }
}

@media (max-width: 768px) {
    
    .actions-column .button {
        margin-bottom: 5px;
        padding: 0;
        font-size: 13px;
        width: 130px;
    }

    .unavailable-product {
        background-color: #dadada !important;

        &:hover {
            background-color: #d2d2d2 !important;
        }
    }
    
    .flex-container {
        flex-direction: column;
        gap: 10px;
        margin-bottom: 15px;
    }

    .form-control, 
    .form-control-category,
    .button-add {
        width: 100%;
        padding: 8px 12px;
        font-size: 14px;
    }

    .button-add {
        text-align: center;
        margin: 0;
        margin-top: -5px;
        height: 2rem;
    }

    .clean-table tbody tr {
        display: block;
        width: 100%;
        margin-bottom: 15px;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        position: relative;
    }

    .clean-table td {
        padding: 5px;
        border: none;
        width: 100%;
        display: block;
    }

    .clean-table td:nth-child(1) {
        /*display: none;*/
        font-size: 10px;
        font-weight: bold;
    }

    /* Third column - product name */
    .clean-table td:nth-child(3) {
        width: 70%;
        font-weight: bold;
        align-items: center;
        margin-bottom: 0;
    }
    .clean-table td:nth-child(4) {
        margin-top: -5px;
        margin-bottom: -5px;
    }

    .options-column {
        font-size: 14px;
    }
    .category-column {
        font-size: 16px;
    }

}
</style>
@endsection
