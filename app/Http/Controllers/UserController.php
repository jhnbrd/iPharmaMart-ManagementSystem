<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    use LogsActivity;
    public function index()
    {
        // Only admin and superadmin can access user management
        if (!in_array(auth()->user()->role, ['admin', 'superadmin'])) {
            return redirect()->route('dashboard')
                ->with('error', 'Unauthorized access.');
        }

        $perPage = request('per_page', \Illuminate\Support\Facades\Cache::get('settings.pagination_per_page', 10));

        // Order users by role priority (superadmin first), then by newest
        // Use CASE expression for cross-DB compatibility
        $users = User::orderByRaw("(CASE WHEN role = 'superadmin' THEN 1 WHEN role = 'admin' THEN 2 WHEN role = 'inventory_manager' THEN 3 WHEN role = 'cashier' THEN 4 ELSE 5 END) ASC")
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return view('users.index', compact('users'));
    }

    public function create()
    {
        if (!in_array(auth()->user()->role, ['admin', 'superadmin'])) {
            return redirect()->route('dashboard')
                ->with('error', 'Unauthorized access.');
        }

        return view('users.create');
    }

    public function store(Request $request)
    {
        try {
            // Role restriction based on current user
            $currentUserRole = auth()->user()->role;

            if ($currentUserRole === 'superadmin') {
                $allowedRoles = ['admin'];
            } elseif ($currentUserRole === 'admin') {
                // Admin can create any role except superadmin
                $allowedRoles = ['admin', 'cashier', 'inventory_manager'];
            } else {
                return redirect()->back()->with('error', 'Unauthorized action.');
            }

            $validated = $request->validate([
                'username' => 'required|string|max:255|unique:users,username|alpha_dash',
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'role' => 'required|in:' . implode(',', $allowedRoles),
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $validated['password'] = Hash::make($validated['password']);
            $validated['is_active'] = true;

            $user = User::create($validated);

            self::logActivity('create', "Created user: {$user->name} ({$user->username})", $user, null, $validated);

            return redirect()->route('users.index')
                ->with('success', 'User added successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to add user: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(User $user)
    {
        if (!in_array(auth()->user()->role, ['admin', 'superadmin'])) {
            return redirect()->route('dashboard')
                ->with('error', 'Unauthorized access.');
        }

        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        try {
            if ($user->role === 'superadmin') {
                return redirect()->back()
                    ->with('error', 'Super Admin accounts cannot be modified.');
            }

            // Role restriction based on current user
            $currentUserRole = auth()->user()->role;

            if ($currentUserRole === 'superadmin') {
                $allowedRoles = ['admin'];
            } elseif ($currentUserRole === 'admin') {
                // Admin can assign any role except superadmin
                $allowedRoles = ['admin', 'cashier', 'inventory_manager'];
            } else {
                return redirect()->back()->with('error', 'Unauthorized action.');
            }

            $oldValues = $user->only(['username', 'name', 'email', 'role']);

            $validated = $request->validate([
                'username' => 'required|string|max:255|unique:users,username,' . $user->id . '|alpha_dash',
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'role' => 'required|in:' . implode(',', $allowedRoles),
                'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            ]);

            if (!empty($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }

            $user->update($validated);

            self::logActivity('update', "Updated user: {$user->name} ({$user->username})", $user, $oldValues, $validated);

            return redirect()->route('users.index')
                ->with('success', 'User updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update user: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(User $user)
    {
        try {
            if (!in_array(auth()->user()->role, ['admin', 'superadmin'])) {
                return redirect()->route('dashboard')
                    ->with('error', 'Unauthorized access.');
            }

            if ($user->role === 'superadmin') {
                return redirect()->back()
                    ->with('error', 'Super Admin accounts cannot be deleted.');
            }

            // Prevent deleting the currently logged-in user
            if ($user->id === Auth::id()) {
                return redirect()->back()
                    ->with('error', 'You cannot delete your own account.');
            }

            $userName = $user->name;
            $userUsername = $user->username;

            $user->delete();

            self::logActivity('delete', "Deleted user: {$userName} ({$userUsername})", null, $user->toArray(), null);

            return redirect()->route('users.index')
                ->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }
}
