<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\SuperAdminMiddleware;

class AdminUserController extends Controller
{
    public function __construct()
    {
        // In Laravel 11, we shouldn't use $this->middleware() in the constructor
        // Instead, we'll register the middleware in the routes file
    }
    
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        // Check if the current user is a super admin
        $isSuperAdmin = Auth::user()->is_super_admin;
        return view('admin.users.create', compact('isSuperAdmin'));
    }

    /**
     * Store a newly created user in the database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'is_admin' => 'boolean',
            'is_super_admin' => 'boolean',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_admin'] = $request->has('is_admin');
        
        // Only allow super admin to set super admin status
        if (Auth::user()->is_super_admin) {
            $validated['is_super_admin'] = $request->has('is_super_admin');
        } else {
            $validated['is_super_admin'] = false;
        }

        User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        // Additional information such as user requests or activity history can be displayed
        $orders = $user->orders()->orderBy('created_at', 'desc')->take(5)->get();
        $isSuperAdmin = Auth::user()->is_super_admin;

        return view('admin.users.show', compact('user', 'orders', 'isSuperAdmin'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $isSuperAdmin = Auth::user()->is_super_admin;
        return view('admin.users.edit', compact('user', 'isSuperAdmin'));
    }

    /**
     * Update the specified user in the database.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'is_admin' => 'boolean',
            'is_super_admin' => 'boolean',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
        ]);

        // Update the password only if it is provided
        if (isset($validated['password']) && $validated['password']) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_admin'] = $request->has('is_admin');

        // Only allow super admin to update super admin status
        if (Auth::user()->is_super_admin) {
            // Prevent removing super admin status from yourself
            if ($user->id === Auth::id() && !$request->has('is_super_admin')) {
                return redirect()->route('admin.users.edit', $user->id)
                    ->with('error', 'You cannot remove your own super admin privileges');
            }
            $validated['is_super_admin'] = $request->has('is_super_admin');
        } else {
            // Non-super admins can't change super admin status
            unset($validated['is_super_admin']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified user from the database.
     */
    public function destroy(User $user)
    {
        // Check if the user has orders
        $ordersCount = $user->orders()->count();

        // Super admins can only be deleted by other super admins
        if ($user->is_super_admin && !Auth::user()->is_super_admin) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You do not have permission to delete a super admin');
        }

        if ($ordersCount > 0) {
            return redirect()->route('admin.users.index')
                ->with('error', 'This user cannot be deleted because they have associated orders');
        }

        // Ensure that the current admin is not deleted
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot delete your own account');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully');
    }

    /**
     * Change the admin status of the user.
     */
    public function toggleAdmin(User $user)
    {
        // Ensure that the current admin's privileges are not revoked
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot revoke your own admin privileges');
        }

        // Super admins must remain admins
        if ($user->is_super_admin && !$user->is_admin) {
            $user->is_admin = true;
            $user->save();
            
            return redirect()->route('admin.users.index')
                ->with('warning', 'Super admins must also be admins. Admin status has been maintained.');
        } else {
            $user->is_admin = !$user->is_admin;
            $user->save();
            
            $status = $user->is_admin ? 'granted' : 'revoked';
            
            return redirect()->route('admin.users.index')
                ->with('success', "Admin privileges have been {$status} successfully");
        }
    }
    
    /**
     * Change the super admin status of the user.
     * Only available to super admins.
     */
    public function toggleSuperAdmin(User $user)
    {
        // Ensure that the current super admin's privileges are not revoked
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot revoke your own super admin privileges');
        }
        
        // Toggle super admin status
        $user->is_super_admin = !$user->is_super_admin;
        
        // Ensure super admins are also regular admins
        if ($user->is_super_admin) {
            $user->is_admin = true;
        }
        
        $user->save();
        
        $status = $user->is_super_admin ? 'granted' : 'revoked';
        
        return redirect()->route('admin.users.index')
            ->with('success', "Super admin privileges have been {$status} successfully");
    }
}