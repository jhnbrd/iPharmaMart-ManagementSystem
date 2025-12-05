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
        $perPage = request('per_page', 10);
        $users = User::orderBy('created_at', 'desc')->paginate($perPage);

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'username' => 'required|string|max:255|unique:users,username|alpha_dash',
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'role' => 'required|in:superadmin,admin,cashier,inventory_manager',
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
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        try {
            $oldValues = $user->only(['username', 'name', 'email', 'role']);

            $validated = $request->validate([
                'username' => 'required|string|max:255|unique:users,username,' . $user->id . '|alpha_dash',
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'role' => 'required|in:superadmin,admin,cashier,inventory_manager',
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
