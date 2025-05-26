<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a list of user orders
     */
    public function index(Request $request)
    {
        $query = Order::with('user')->orderBy('id', 'asc');
        // Check if the user is logged in
        if (!$request->user()) {
            return redirect()->route('login')->with('error', 'Please log in to view your orders');
        }

        // Get the current user's orders, ordered from newest to oldest
        $orders = Order::where('user_id', $request->user()->id)
                       ->orderBy('created_at', 'desc')
                       ->with('items.product')
                       ->get();

        return view('orders.index', compact('orders'));
    }

    /**
     * Display details of a specific order
     */
    public function show(Request $request, Order $order)
    {
        // Check if the user is logged in
        if (!$request->user()) {
            return redirect()->route('login')->with('error', 'Please log in to view order details');
        }

        // Ensure the order belongs to the current user
        if ($order->user_id !== $request->user()->id) {
            abort(403, 'You are not authorized to view this order');
        }

        return view('orders.show', compact('order'));
    }

    /**
     * Display the order success page
     */
    public function success(Request $request, Order $order)
    {
        // Check if the user is logged in
        if (!$request->user()) {
            return redirect()->route('login')->with('error', 'Please log in to view order details');
        }

        // Ensure the order belongs to the current user
        if ($order->user_id !== $request->user()->id) {
            abort(403, 'You are not authorized to view this order');
        }

        return view('orders.success', compact('order'));
    }

    /**
     * Display the order invoice
     */
    public function invoice(Request $request, Order $order)
    {
        // Check if the user is logged in
        if (!$request->user()) {
            return redirect()->route('login')->with('error', 'Please log in to view the invoice');
        }

        // Ensure the order belongs to the current user
        if ($order->user_id !== $request->user()->id) {
            abort(403, 'You are not authorized to view this invoice');
        }

        return view('orders.invoice', compact('order'));
    }

    /**
     * Display the shopping cart
     */
    public function showCart(Request $request)
    {
        // Check if the user is logged in
        if (!$request->user()) {
            return view('cart.guest_message');
        }

        $cart = $this->getOrCreateCart($request->user()->id);

        return view('cart.show', compact('cart'));
    }

    /**
     * Add a product to the shopping cart
     */
    public function addToCart(Request $request, Product $product)
    {
        // Check if the user is logged in
        if (!$request->user()) {
            return redirect()->route('login')->with('error', 'Please log in to add to cart');
        }

        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = $this->getOrCreateCart($request->user()->id);

        // Check if the product already exists in the cart
        $cartItem = $cart->items()->where('product_id', $product->id)->first();

        if ($cartItem) {
            // Update quantity if the product already exists
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            // Create a new item if the product does not exist
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $request->quantity
            ]);
        }

        return redirect()->route('cart.show')->with('success', 'Product added to cart successfully');
    }

    /**
     * Update the quantity of a product in the shopping cart
     */
    public function updateQuantity(Request $request, CartItem $cartItem)
    {
        // Check if the user is logged in
        if (!$request->user()) {
            return redirect()->route('login')->with('error', 'Please log in to update the cart');
        }

        // Ensure the cart item belongs to the current user
        if ($cartItem->cart->user_id !== $request->user()->id) {
            abort(403, 'You are not authorized to modify this item');
        }

        $quantity = $cartItem->quantity;

        if ($request->has('increment')) {
            $quantity += 1;
        } elseif ($request->has('decrement')) {
            $quantity = max(1, $quantity - 1);
        } else {
            $request->validate([
                'quantity' => 'required|integer|min:1'
            ]);
            $quantity = $request->quantity;
        }

        $cartItem->quantity = $quantity;
        $cartItem->save();

        return redirect()->route('cart.show')->with('success', 'Quantity updated successfully');
    }

    /**
     * Remove a product from the shopping cart
     */
    public function removeFromCart(Request $request, CartItem $cartItem)
    {
        // Check if the user is logged in
        if (!$request->user()) {
            return redirect()->route('login')->with('error', 'Please log in to remove items from the cart');
        }

        // Ensure the cart item belongs to the current user
        if ($cartItem->cart->user_id !== $request->user()->id) {
            abort(403, 'You are not authorized to delete this item');
        }

        $cartItem->delete();

        return redirect()->route('cart.show')->with('success', 'Product removed from cart successfully');
    }

    /**
     * Clear the shopping cart
     */
    public function clearCart(Request $request)
    {
        // Check if the user is logged in
        if (!$request->user()) {
            return redirect()->route('login')->with('error', 'Please log in to clear the shopping cart');
        }

        $cart = $this->getOrCreateCart($request->user()->id);
        $cart->items()->delete();

        return redirect()->route('cart.show')->with('success', 'Shopping cart cleared successfully');
    }

    /**
     * Get the current shopping cart or create a new one
     */
    private function getOrCreateCart($userId)
    {
        $cart = Cart::firstOrCreate(
            ['user_id' => $userId]
        );

        return $cart->load('items.product');
    }

    /**
     * Apply a discount coupon
     */
    public function applyCoupon(Request $request)
    {
        // Check if the user is logged in
        if (!$request->user()) {
            return redirect()->route('login')->with('error', 'Please log in to apply a discount coupon');
        }

        $request->validate([
            'coupon_code' => 'required|string|max:50'
        ]);

        // Implement coupon validation logic here
        // You can use a Coupon model to check coupon validity

        // This is a simple example of coupon validation (you will need to customize it according to your system)
        $couponCode = $request->coupon_code;
        $discountAmount = 0;

        // Add your coupon validation logic here

        // Add the discount to the shopping cart
        $cart = $this->getOrCreateCart($request->user()->id);
        $cart->coupon_code = $couponCode;
        $cart->discount_amount = $discountAmount;
        $cart->save();

        return redirect()->route('cart.show')->with('success', 'Coupon applied successfully');
    }

    /**
     * Process payment and create the order
     */
    public function processPayment(Request $request)
    {
        // Check if the user is logged in
        if (!$request->user()) {
            return redirect()->route('login')->with('error', 'Please log in to complete the purchase');
        }

        $cart = $this->getOrCreateCart($request->user()->id);

        // Check if there are products in the cart
        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.show')->with('error', 'No products in the shopping cart');
        }

        // Validate the entered data
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'payment_method' => 'required|in:credit_card,cash_on_delivery',
        ]);

        // Create a new order
        $order = new Order();
        $order->user_id = $request->user()->id;
        $order->first_name = $validatedData['first_name'];
        $order->last_name = $validatedData['last_name'];
        $order->email = $validatedData['email'];
        $order->phone = $validatedData['phone'];
        $order->shipping_address = $validatedData['address'];
        $order->payment_method = $validatedData['payment_method'];
        $order->status = 'pending';

        // Calculate the order total
        $subtotal = $cart->items->sum(function($item) {
            return ($item->product->discount_price ?? $item->product->price) * $item->quantity;
        });

        $shippingCost = 20; // Fixed shipping cost
        $taxRate = 0.15; // Tax rate 15%
        $tax = $subtotal * $taxRate;

        $order->subtotal = $subtotal;
        $order->shipping_cost = $shippingCost;
        $order->tax = $tax;
        $order->total_amount = $subtotal + $shippingCost + $tax;
        $order->save();

        // Create order items
        foreach ($cart->items as $cartItem) {
            $product = $cartItem->product;

            // Check product availability before adding
            if (!$product || $product->stock < $cartItem->quantity) {
                return redirect()->route('cart.show')->with('error', 'Some products are not available in the required quantity');
            }

            // Update stock
            $product->stock -= $cartItem->quantity;
            $product->save();

            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $cartItem->product_id;
            $orderItem->quantity = $cartItem->quantity;
            $orderItem->price_at_order = $cartItem->product->discount_price ?? $cartItem->product->price;
            $orderItem->save();
        }

        // Clear the shopping cart
        $cart->items()->delete();

        // Process payment based on the selected payment method
        if ($validatedData['payment_method'] == 'credit_card') {
            // Process credit card payment here
            // You can use a payment gateway like Stripe or PayPal
        }

        return redirect()->route('checkout.success', $order)->with('success', 'Order created successfully');
    }

    /**
     * Cancel the order
     */
    public function cancel(Request $request, Order $order)
    {
        // Check if the user is logged in
        if (!$request->user()) {
            return redirect()->route('login')->with('error', 'Please log in to cancel the order');
        }

        // Ensure the order belongs to the current user
        if ($order->user_id !== $request->user()->id) {
            abort(403, 'You are not authorized to cancel this order');
        }

        // Ensure the order status allows cancellation
        if (!in_array($order->status, ['pending', 'processing'])) {
            return redirect()->back()->with('error', 'This order cannot be cancelled in its current status');
        }

        // Update the order status
        $order->status = 'cancelled';
        $order->save();

        return redirect()->back()->with('success', 'Order cancelled successfully');
    }
}