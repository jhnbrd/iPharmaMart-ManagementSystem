<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    use LogsActivity;
    public function index()
    {
        $perPage = request('per_page', \Illuminate\Support\Facades\Cache::get('settings.pagination_per_page', 10));
        $suppliers = Supplier::withCount('products')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|min:2',
                'contact_person' => 'nullable|string|max:255',
                'email' => 'nullable|email:rfc,dns|unique:suppliers,email|max:255',
                'phone' => 'nullable|string|max:20|regex:/^([0-9\s\-\+\(\)]*)$/',
                'address' => 'nullable|string|max:500',
            ], [
                'name.min' => 'Supplier name must be at least 2 characters.',
                'phone.regex' => 'Phone number format is invalid. Use numbers, spaces, +, -, or parentheses.',
                'email.email' => 'Please provide a valid email address.',
            ]);

            $supplier = Supplier::create($validated);

            self::logActivity(
                'create',
                "Created supplier: {$supplier->name}",
                $supplier,
                null,
                $supplier->toArray()
            );

            return redirect()->route('suppliers.index')
                ->with('success', 'Supplier added successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to add supplier: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        try {
            $oldValues = $supplier->toArray();

            $validated = $request->validate([
                'name' => 'required|string|max:255|min:2',
                'contact_person' => 'nullable|string|max:255',
                'email' => 'nullable|email:rfc,dns|unique:suppliers,email,' . $supplier->id . '|max:255',
                'phone' => 'nullable|string|max:20|regex:/^([0-9\s\-\+\(\)]*)$/',
                'address' => 'nullable|string|max:500',
            ], [
                'name.min' => 'Supplier name must be at least 2 characters.',
                'phone.regex' => 'Phone number format is invalid. Use numbers, spaces, +, -, or parentheses.',
                'email.email' => 'Please provide a valid email address.',
            ]);

            $supplier->update($validated);

            self::logActivity(
                'update',
                "Updated supplier: {$supplier->name}",
                $supplier,
                $oldValues,
                $supplier->fresh()->toArray()
            );

            return redirect()->route('suppliers.index')
                ->with('success', 'Supplier updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update supplier: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Supplier $supplier)
    {
        try {
            // Check if supplier has any products
            if ($supplier->products()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Cannot delete supplier with existing products.');
            }

            $supplierName = $supplier->name;
            $supplierData = $supplier->toArray();

            $supplier->delete();

            self::logActivity(
                'delete',
                "Deleted supplier: {$supplierName}",
                null,
                $supplierData,
                null
            );

            return redirect()->route('suppliers.index')
                ->with('success', 'Supplier deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete supplier: ' . $e->getMessage());
        }
    }
}
