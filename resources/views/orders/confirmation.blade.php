<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-lg shadow-lg p-8">
                <div class="text-center mb-8">
                    <svg class="w-16 h-16 text-green-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Order Confirmed!</h1>
                    <p class="text-gray-600">Thank you for your order. Your order number is #{{ $order->id }}</p>
                </div>

                <div class="border-t border-gray-200 pt-8">
                    <h2 class="text-xl font-semibold mb-4">Order Details</h2>
                    @foreach($order->items as $item)
                        <div class="flex items-center justify-between py-4 border-b border-gray-200">
                            <div class="flex items-center">
                                @if($item->product->image)
                                    <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="w-16 h-16 object-cover rounded">
                                @else
                                    <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                        <span class="text-gray-400">No image</span>
                                    </div>
                                @endif
                                <div class="ml-4">
                                    <h3 class="font-semibold">{{ $item->product->name }}</h3>
                                    <p class="text-sm text-gray-600">Quantity: {{ $item->quantity }}</p>
                                </div>
                            </div>
                            <span class="font-semibold">${{ number_format($item->price, 2) }}</span>
                        </div>
                    @endforeach

                    <div class="mt-6 text-right">
                        <p class="text-gray-600">Total Amount:</p>
                        <p class="text-2xl font-bold">${{ number_format($order->total_amount, 2) }}</p>
                    </div>

                    <div class="mt-8 p-4 bg-blue-50 rounded-lg">
                        <h3 class="font-semibold text-blue-900 mb-2">Payment Method: Cash on Delivery</h3>
                        <p class="text-blue-800">Please have the exact amount ready when your order arrives.</p>
                    </div>

                    <div class="mt-8 text-center">
                        <a href="{{ route('home') }}" class="inline-block bg-blue-600 text-white py-3 px-6 rounded-lg hover:bg-blue-700 transition">
                            Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
