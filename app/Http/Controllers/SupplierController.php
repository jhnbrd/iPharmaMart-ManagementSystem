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
        $suppliers = Supplier::withCount('products')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

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
                'name' => 'required|string|max:255',
                'email' => 'nullable|email|unique:suppliers,email',
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string',
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
                'name' => 'required|string|max:255',
                'email' => 'nullable|email|unique:suppliers,email,' . $supplier->id,
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string',
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
