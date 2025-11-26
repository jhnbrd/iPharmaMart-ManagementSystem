<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    use LogsActivity;
    public function index()
    {
        $customers = Customer::withCount('sales')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'nullable|email|unique:customers,email',
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string',
            ]);

            $customer = Customer::create($validated);

            self::logActivity(
                'create',
                "Created customer: {$customer->name}",
                $customer,
                null,
                $customer->toArray()
            );

            return redirect()->route('customers.index')
                ->with('success', 'Customer added successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to add customer: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        try {
            $oldValues = $customer->toArray();

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'nullable|email|unique:customers,email,' . $customer->id,
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string',
            ]);

            $customer->update($validated);

            self::logActivity(
                'update',
                "Updated customer: {$customer->name}",
                $customer,
                $oldValues,
                $customer->fresh()->toArray()
            );

            return redirect()->route('customers.index')
                ->with('success', 'Customer updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update customer: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Customer $customer)
    {
        try {
            // Check if customer has any sales
            if ($customer->sales()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Cannot delete customer with existing sales records.');
            }

            $customerName = $customer->name;
            $customerData = $customer->toArray();

            $customer->delete();

            self::logActivity(
                'delete',
                "Deleted customer: {$customerName}",
                null,
                $customerData,
                null
            );

            return redirect()->route('customers.index')
                ->with('success', 'Customer deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete customer: ' . $e->getMessage());
        }
    }
}
