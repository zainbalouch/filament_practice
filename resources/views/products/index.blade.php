<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8">Our Products</h1>

        <!-- Category Filter -->
        <div class="mb-8">
            <h2 class="text-xl font-semibold mb-4">Categories</h2>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('home') }}"
                   class="px-4 py-2 rounded-full {{ !request('category') ? 'bg-blue-500 text-white' : 'bg-white text-gray-700' }} hover:bg-blue-600 hover:text-white transition">
                    All
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('home', ['category' => $category->id]) }}"
                       class="px-4 py-2 rounded-full {{ request('category') == $category->id ? 'bg-blue-500 text-white' : 'bg-white text-gray-700' }} hover:bg-blue-600 hover:text-white transition">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($products as $product)
                <a href="{{ route('products.show', $product) }}" class="block hover:shadow-lg transition duration-300">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}"
                                 alt="{{ $product->name }}"
                                 class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-400">No image</span>
                            </div>
                        @endif
                        <div class="p-4">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="text-lg font-semibold">{{ $product->name }}</h3>
                                <span class="text-lg font-bold text-blue-600">${{ number_format($product->price, 2) }}</span>
                            </div>
                            <p class="text-sm text-gray-600 mb-2">{{ $product->category->name }}</p>
                            <div class="text-sm text-gray-500 mb-2">
                                {!! Str::limit(strip_tags($product->description), 100) !!}
                            </div>
                            <div class="mt-3 text-sm">
                                <span class="text-gray-600">
                                    Stock: <span class="font-semibold {{ $product->quantity > 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $product->quantity > 0 ? $product->quantity . ' units' : 'Out of stock' }}
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $products->withQueryString()->links() }}
        </div>
    </div>
</body>
</html>
