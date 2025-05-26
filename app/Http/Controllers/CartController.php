<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * Display the shopping cart
     */
    public function show(Request $request)
    {
        // Check if the user is logged in
        if (!Auth::check()) {
            return view('cart.guest_message');
        }

        $cart = $this->getOrCreateCart(Auth::id());
        
        // Calculate shipping and other cart details
        $shipping = 20.00; // Could be moved to a configuration or based on cart weight/location
        
        return view('cart.show', compact('cart', 'shipping'));
    }

    /**
     * Add a product to the shopping cart
     */
    public function addToCart(Request $request, Product $product)
    {
        // Check if the user is logged in
        if (!Auth::check()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please login to add to cart',
                    'redirect' => route('login')
                ]);
            }
            return redirect()->route('login')->with('error', 'Please login to add to cart');
        }

        // Validate the request
        $request->validate([
            'quantity' => 'required|integer|min:1|max:99'
        ]);

        // Check if product is active and in stock
        if (!$product->is_active) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This product is not available.'
                ]);
            }
            return back()->with('error', 'This product is not available.');
        }

        $cart = $this->getOrCreateCart(Auth::id());

        // Check if the product already exists in the cart
        $cartItem = $cart->items()->where('product_id', $product->id)->first();

        if ($cartItem) {
            // Update the quantity if the product already exists
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            // Create a new item if the product does not exist
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $request->quantity
            ]);
        }

        // Get updated cart count and total
        $cartItemCount = $cart->items->count();
        $cartTotal = $cart->total;
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Product added to cart successfully',
                'cartCount' => $cartItemCount,
                'cartTotal' => number_format($cartTotal, 2)
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
        if (!Auth::check()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please login to update the cart',
                    'redirect' => route('login')
                ]);
            }
            return redirect()->route('login')->with('error', 'Please login to update the cart');
        }

        // Ensure the cart item belongs to the current user
        if ($cartItem->cart->user_id !== Auth::id()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to modify this item'
                ], 403);
            }
            abort(403, 'You are not authorized to modify this item');
        }

        $quantity = $cartItem->quantity;

        if ($request->has('increment')) {
            $quantity += 1;
        } elseif ($request->has('decrement')) {
            $quantity = max(1, $quantity - 1);
        } else {
            $request->validate([
                'quantity' => 'required|integer|min:1|max:99'
            ]);
            $quantity = $request->quantity;
        }

        $cartItem->quantity = $quantity;
        $cartItem->save();
        
        // Reload cart with fresh data
        $cart = $cartItem->cart->fresh('items.product');
        $shipping = 20.00;
        $itemSubtotal = $cartItem->subtotal;
        $cartSubtotal = $cart->total;
        $cartTotal = $cartSubtotal + $shipping;
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Quantity updated successfully',
                'itemQuantity' => $quantity,
                'itemSubtotal' => number_format($itemSubtotal, 2),
                'cartSubtotal' => number_format($cartSubtotal, 2),
                'cartTotal' => number_format($cartTotal, 2)
            ]);
        }

        return redirect()->route('cart.show')->with('success', 'Quantity updated successfully');
    }

    /**
     * Remove a product from the shopping cart
     */
    public function removeFromCart(Request $request, CartItem $cartItem)
    {
        // Check if the user is logged in
        if (!Auth::check()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please login to remove products from the cart',
                    'redirect' => route('login')
                ]);
            }
            return redirect()->route('login')->with('error', 'Please login to remove products from the cart');
        }

        // Ensure the cart item belongs to the current user
        if ($cartItem->cart->user_id !== Auth::id()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to delete this item'
                ], 403);
            }
            abort(403, 'You are not authorized to delete this item');
        }
        
        // Get cart before deleting the item
        $cart = $cartItem->cart;
        
        // Delete the item
        $cartItem->delete();
        
        // Refresh cart data
        $cart = $cart->fresh('items.product');
        $shipping = 20.00;
        $cartSubtotal = $cart->total;
        $cartTotal = $cartSubtotal + $shipping;
        $cartItemCount = $cart->items->count();
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Product removed from cart',
                'cartSubtotal' => number_format($cartSubtotal, 2),
                'cartTotal' => number_format($cartTotal, 2),
                'cartItemCount' => $cartItemCount,
                'isEmpty' => $cartItemCount === 0
            ]);
        }

        return redirect()->route('cart.show')->with('success', 'Product removed from cart');
    }

    /**
     * Clear the shopping cart
     */
    public function clearCart(Request $request)
    {
        // Check if the user is logged in
        if (!Auth::check()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please login to clear the shopping cart',
                    'redirect' => route('login')
                ]);
            }
            return redirect()->route('login')->with('error', 'Please login to clear the shopping cart');
        }

        $cart = $this->getOrCreateCart(Auth::id());
        $cart->items()->delete();
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Shopping cart cleared',
                'redirect' => route('cart.show')
            ]);
        }

        return redirect()->route('cart.show')->with('success', 'Shopping cart cleared');
    }

    /**
     * Apply a discount coupon
     */
    public function applyCoupon(Request $request)
    {
        // Check if the user is logged in
        if (!Auth::check()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please login to apply a discount coupon',
                    'redirect' => route('login')
                ]);
            }
            return redirect()->route('login')->with('error', 'Please login to apply a discount coupon');
        }

        $request->validate([
            'coupon_code' => 'required|string|max:50'
        ]);

        // Implement coupon validation logic here
        // Example implementation (you would need to create a Coupon model):
        $couponCode = $request->coupon_code;
        
        // For demo purposes, let's assume a 10% discount for code "SAVE10"
        if (strtoupper($couponCode) === 'SAVE10') {
            $cart = $this->getOrCreateCart(Auth::id());
            $discountAmount = $cart->total * 0.10;
            
            // Store discount information in session
            Session::put('coupon_applied', true);
            Session::put('coupon_code', $couponCode);
            Session::put('discount_amount', $discountAmount);
            Session::put('coupon_status', 'success');
            Session::put('coupon_message', 'Coupon applied successfully. You got 10% off!');
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Coupon applied successfully. You got 10% off!',
                    'discountAmount' => number_format($discountAmount, 2),
                    'cartTotal' => number_format(($cart->total + 20 - $discountAmount), 2)
                ]);
            }
            
            return redirect()->route('cart.show')->with('success', 'Coupon applied successfully. You got 10% off!');
        } 
        
        // Invalid coupon code
        Session::put('coupon_status', 'error');
        Session::put('coupon_message', 'Invalid coupon code. Please try again.');
        
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid coupon code. Please try again.'
            ]);
        }
        
        return redirect()->route('cart.show')->with('error', 'Invalid coupon code. Please try again.');
    }
    
    /**
     * Get cart count for navigation
     */
    public function getCartCount()
    {
        if (!Auth::check()) {
            return response()->json(['count' => 0]);
        }
        
        $cart = $this->getOrCreateCart(Auth::id());
        $count = $cart->items->count();
        
        return response()->json(['count' => $count]);
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
}