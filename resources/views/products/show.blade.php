<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <a href="{{ route('home') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Products
            </a>
            <a href="{{ route('cart.show') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                View Cart ({{ count(session('cart', [])) }})
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="md:flex">
                <div class="md:w-1/2">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}"
                             alt="{{ $product->name }}"
                             class="w-full h-96 object-cover">
                    @else
                        <div class="w-full h-96 bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-400">No image</span>
                        </div>
                    @endif
                </div>
                <div class="md:w-1/2 p-8">
                    <div class="mb-4">
                        <h1 class="text-3xl font-bold mb-2">{{ $product->name }}</h1>
                        <p class="text-sm text-gray-600">Category: {{ $product->category->name }}</p>
                    </div>

                    <div class="mb-6">
                        <span class="text-2xl font-bold text-blue-600">${{ number_format($product->price, 2) }}</span>
                    </div>

                    <div class="mb-6">
                        <span class="text-gray-600">
                            Stock: <span class="font-semibold {{ $product->quantity > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $product->quantity > 0 ? $product->quantity . ' units' : 'Out of stock' }}
                            </span>
                        </span>
                    </div>

                    @if($product->quantity > 0)
                        <div class="space-y-4">
                            <div class="flex items-center gap-4 mb-4">
                                <label for="quantity" class="text-gray-700">Quantity:</label>
                                <input type="number" name="quantity" id="quantity"
                                    min="1"
                                    max="{{ $product->quantity }}"
                                    value="1"
                                    class="rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 w-20">
                            </div>
                            <div class="flex gap-4">
                                <form action="{{ route('cart.add', $product) }}" method="POST" class="flex-1">
                                    @csrf
                                    <input type="hidden" name="quantity" id="cart_quantity">
                                    <button type="submit" class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg hover:bg-blue-700 transition">
                                        Add to Cart
                                    </button>
                                </form>

                                <a href="{{ route('checkout.show', $product) }}" class="flex-1" id="buy_now_link">
                                    <button type="button" class="w-full bg-green-600 text-white py-3 px-6 rounded-lg hover:bg-green-700 transition">
                                        Buy Now
                                    </button>
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="space-y-4">
                            <p class="text-red-600 font-semibold">Out of stock</p>
                            <div class="flex gap-4">
                                <button disabled class="flex-1 bg-gray-400 text-white py-3 px-6 rounded-lg cursor-not-allowed">
                                    Add to Cart
                                </button>
                                <button disabled class="flex-1 bg-gray-400 text-white py-3 px-6 rounded-lg cursor-not-allowed">
                                    Buy Now
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Product Description -->
            <div class="p-8 border-t border-gray-200">
                <h2 class="text-2xl font-bold mb-4">Product Description</h2>
                <div class="prose max-w-none">
                    {!! $product->description !!}
                </div>
            </div>
        </div>
    </div>

    <script>
        // Sync quantity between input and cart form
        const quantityInput = document.getElementById('quantity');
        const cartQuantityInput = document.getElementById('cart_quantity');
        const buyNowLink = document.getElementById('buy_now_link');
        const originalBuyNowHref = buyNowLink.href;

        function syncQuantity() {
            const value = Math.min(Math.max(1, parseInt(quantityInput.value) || 1), parseInt(quantityInput.max));
            quantityInput.value = value;
            cartQuantityInput.value = value;
            // Update buy now link with quantity
            buyNowLink.href = `${originalBuyNowHref}?quantity=${value}`;
        }

        quantityInput.addEventListener('input', syncQuantity);
        quantityInput.addEventListener('change', syncQuantity);
        syncQuantity(); // Initial sync
    </script>
</body>
</html>
