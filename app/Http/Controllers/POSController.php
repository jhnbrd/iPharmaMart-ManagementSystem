<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Customer;
use App\Models\Category;
use Illuminate\Http\Request;

class POSController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'supplier'])
            ->whereRaw('(shelf_stock + back_stock) > 0');

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('product_type')) {
            $query->where('product_type', $request->product_type);
        }

        $products = $query->orderBy('name')->paginate(10);

        $customers = Customer::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('pos.index', compact('products', 'customers', 'categories'));
    }

    public function verifyAdmin(Request $request)
    {
        try {
            $validated = $request->validate([
                'username' => 'required|string',
                'password' => 'required|string',
                'action' => 'required|string',
                'item_id' => 'nullable|integer',
                'reason' => 'required|string',
            ]);

            // Verify admin credentials
            $admin = \App\Models\User::where('username', $validated['username'])
                ->whereIn('role', ['admin', 'superadmin'])
                ->first();

            if (!$admin || !\Illuminate\Support\Facades\Hash::check($validated['password'], $admin->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid admin credentials. Only admin users can perform this action.'
                ], 401);
            }

            // Log the void action
            $actionDescription = $validated['action'] === 'void_entire_sale'
                ? "Voided entire sale in POS - Reason: {$validated['reason']} - Authorized by: {$admin->name}"
                : "Voided item from POS cart - Item ID: {$validated['item_id']} - Reason: {$validated['reason']} - Authorized by: {$admin->name}";

            \App\Traits\LogsActivity::logActivity(
                $validated['action'] === 'void_entire_sale' ? 'void_entire_sale_pos' : 'void_item_pos',
                $actionDescription,
                null,
                null,
                [
                    'action' => $validated['action'],
                    'item_id' => $validated['item_id'],
                    'reason' => $validated['reason'],
                    'authorized_by' => $admin->name,
                    'authorized_by_username' => $admin->username,
                ]
            );

            return response()->json([
                'success' => true,
                'admin_name' => $admin->name,
                'message' => 'Authorization successful'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }
}
