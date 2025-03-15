<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'payment_method' => ['required', 'in:cash_on_delivery'],
            'quantity' => ['required', 'integer', 'min:1'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string'],
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->quantity < 1) {
            return redirect()->back()->with('error', 'Sorry, this product is out of stock.');
        }

        $quantity = min($request->quantity, $product->quantity);

        if ($quantity < 1) {
            return redirect()->back()->with('error', 'Invalid quantity requested.');
        }

        // Create order
        $order = Order::create([
            'user_id' => auth()->id(),
            'total_amount' => $product->price * $quantity,
            'payment_method' => $request->payment_method,
            'status' => 'pending',
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        // Add order item
        $order->items()->create([
            'product_id' => $product->id,
            'quantity' => $quantity,
            'price' => $product->price,
        ]);

        // Decrease product quantity
        $product->decrement('quantity', $quantity);

        return redirect()->route('orders.confirmation', $order)
            ->with('success', 'Order placed successfully! You will pay on delivery.');
    }

    public function storeCart(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string'],
            'payment_method' => ['required', 'in:cash_on_delivery'],
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->back()->with('error', 'Your cart is empty.');
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // Create order
        $order = Order::create([
            'user_id' => auth()->id(),
            'total_amount' => $total,
            'payment_method' => $request->payment_method,
            'status' => 'pending',
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        // Add order items and update stock
        foreach ($cart as $productId => $item) {
            $product = Product::findOrFail($productId);

            if ($product->quantity < $item['quantity']) {
                return redirect()->back()->with('error', "Sorry, {$product->name} is out of stock.");
            }

            $order->items()->create([
                'product_id' => $productId,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);

            $product->decrement('quantity', $item['quantity']);
        }

        // Clear the cart
        session()->forget('cart');

        return redirect()->route('orders.confirmation', $order)
            ->with('success', 'Order placed successfully! You will pay on delivery.');
    }

    public function myOrders()
    {
        $orders = Order::where('user_id', auth()->id())
            ->with(['items.product'])
            ->latest()
            ->paginate(10);

        return view('orders.my-orders', compact('orders'));
    }

    public function confirmation(Order $order)
    {
        // Add authorization check
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        return view('orders.confirmation', compact('order'));
    }

    public function checkout(Product $product)
    {
        if ($product->quantity < 1) {
            return redirect()->back()->with('error', 'Sorry, this product is out of stock.');
        }

        $quantity = request()->query('quantity', 1);
        $quantity = min(max(1, (int)$quantity), $product->quantity);

        return view('orders.checkout', compact('product', 'quantity'));
    }
}
