<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    /**
     * Display the main checkout page
     */
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please login to complete your purchase');
        }
        
        $cart = Cart::with('items.product')->where('user_id', $user->id)->first();
        
        return view('checkout.index', compact('cart'));
    }
    
    /**
     * Process customer data and display invoice for review
     */
    public function invoice(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please login to complete your purchase');
        }
        
        // Validate input data
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'payment_method' => 'required|in:credit_card,paypal,apple_pay,google_pay,bank_transfer,cod',
        ]);
        
        $cart = Cart::with('items.product')->where('user_id', $user->id)->first();
        
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('products.index')->with('error', 'Your shopping cart is empty');
        }
        
        // Store customer data in session
        $request->session()->put('checkout_data', $validated);
        
        // Calculate costs
        $subtotal = $cart->items->sum(function($item) {
            return ($item->product->discount_price ?? $item->product->price) * $item->quantity;
        });
        
        $shippingCost = 20; // Fixed shipping cost
        $taxRate = 0.15; // 15% tax rate
        $tax = $subtotal * $taxRate;
        $totalAmount = $subtotal + $shippingCost + $tax;
        
        return view('checkout.invoice', [
            'customerData' => $validated,
            'cart' => $cart,
            'subtotal' => $subtotal,
            'shippingCost' => $shippingCost,
            'tax' => $tax,
            'totalAmount' => $totalAmount
        ]);
    }
    
    /**
     * Confirm the order and create a new order in the database
     */
    public function confirm(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please login to complete your purchase');
        }
        
        // Retrieve customer data from session
        $customerData = $request->session()->get('checkout_data');
        
        if (!$customerData) {
            return redirect()->route('checkout')->with('error', 'An error occurred processing your data. Please try again.');
        }
        
        $cart = Cart::with('items.product')->where('user_id', $user->id)->first();
        
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('products.index')->with('error', 'Your shopping cart is empty');
        }
        
        // Calculate costs
        $subtotal = $cart->items->sum(function($item) {
            return ($item->product->discount_price ?? $item->product->price) * $item->quantity;
        });
        
        $shippingCost = 20; // Fixed shipping cost
        $taxRate = 0.15; // 15% tax rate
        $tax = $subtotal * $taxRate;
        $totalAmount = $subtotal + $shippingCost + $tax;
        
        // Create new order
        $order = new Order();
        $order->user_id = $user->id;
        $order->first_name = $customerData['first_name'];
        $order->last_name = $customerData['last_name'];
        $order->email = $customerData['email'];
        $order->phone = $customerData['phone'];
        $order->shipping_address = $customerData['address'];
        $order->payment_method = $customerData['payment_method'];
        $order->status = 'pending';
        $order->subtotal = $subtotal;
        $order->shipping_cost = $shippingCost;
        $order->tax = $tax;
        $order->total_amount = $totalAmount;
        $order->save();
        
        // Create order items
        foreach ($cart->items as $cartItem) {
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $cartItem->product_id;
            $orderItem->quantity = $cartItem->quantity;
            $orderItem->price_at_order = $cartItem->product->discount_price ?? $cartItem->product->price;
            $orderItem->save();
        }
        
        // Empty the cart
        $cart->items()->delete();
        
        // Process payment based on selected payment method
        if ($customerData['payment_method'] == 'credit_card') {
            // Implement credit card payment processing here
            // You can use payment gateways like Stripe or PayPal
            // For now we'll just proceed as if payment was successful
            $order->status = 'paid';
            $order->save();
        } elseif ($customerData['payment_method'] == 'paypal') {
            // Implement PayPal processing
            $order->status = 'pending_payment';
            $order->save();
        } else {
            // Other payment methods
            $order->status = 'pending_payment';
            $order->save();
        }
        
        // Clear checkout data from session
        $request->session()->forget('checkout_data');
        
        return redirect()->route('checkout.success', $order);
    }
    
    /**
     * Display order success page
     */
    public function success(Order $order)
    {
        $user = Auth::user();
        
        // Ensure the order belongs to the current user
        if (!$user || $order->user_id !== $user->id) {
            return redirect()->route('home')->with('error', 'You are not authorized to view this order');
        }
        
        // Load order items and related products
        $order->load('items.product');
        
        return view('checkout.success', compact('order'));
    }
}