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
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
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
    }

    public function destroy(Customer $customer)
    {
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
    }
}
