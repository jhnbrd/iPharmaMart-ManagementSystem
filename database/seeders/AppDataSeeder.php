<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Facades\Hash;

class AppDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@ipharmamart.com',
            'password' => Hash::make('password'),
        ]);

        // Create Categories
        $categories = [
            ['name' => 'Pain Relief', 'description' => 'Pain management medications'],
            ['name' => 'Antibiotics', 'description' => 'Antibiotic medications'],
            ['name' => 'Supplements', 'description' => 'Vitamins and supplements'],
            ['name' => 'First Aid', 'description' => 'First aid supplies'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Create Suppliers
        $suppliers = [
            [
                'name' => 'MedSupply Co',
                'email' => 'contact@medsupply.com',
                'phone' => '+63 912 345 6789',
                'address' => '123 J.P. Laurel Ave, Bajada, Davao City, 8000 Davao del Sur'
            ],
            [
                'name' => 'PharmaCorp',
                'email' => 'sales@pharmacorp.com',
                'phone' => '+63 917 234 5678',
                'address' => '456 C.M. Recto Street, Poblacion District, Davao City, 8000 Davao del Sur'
            ],
            [
                'name' => 'HealthPlus',
                'email' => 'orders@healthplus.com',
                'phone' => '+63 928 765 4321',
                'address' => '789 San Pedro Street, Agdao, Davao City, 8000 Davao del Sur'
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }

        // Create Customers (matching Figma design)
        $customers = [
            [
                'name' => 'John Smith',
                'email' => 'john.smith@email.com',
                'phone' => '+63 905 123 4567',
                'address' => '101 Roxas Avenue, Matina, Davao City, 8000 Davao del Sur'
            ],
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah.j@email.com',
                'phone' => '+63 918 987 6543',
                'address' => '202 Quimpo Boulevard, Ecoland, Davao City, 8000 Davao del Sur'
            ],
            [
                'name' => 'Mike Wilson',
                'email' => 'mike.w@email.com',
                'phone' => '+63 927 456 7890',
                'address' => '303 McArthur Highway, Buhangin, Davao City, 8000 Davao del Sur'
            ],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }

        // Create Products (matching Figma design)
        $products = [
            [
                'name' => 'Paracetamol 500mg',
                'category_id' => 1, // Pain Relief
                'supplier_id' => 1,
                'stock' => 150,
                'low_stock_threshold' => 100,
                'price' => 5.99,
                'expiry_date' => '2025-06-15',
                'description' => 'Pain relief and fever reducer'
            ],
            [
                'name' => 'Amoxicillin 250mg',
                'category_id' => 2, // Antibiotics
                'supplier_id' => 2,
                'stock' => 75,
                'low_stock_threshold' => 100,
                'price' => 12.50,
                'expiry_date' => '2024-12-20',
                'description' => 'Antibiotic for bacterial infections'
            ],
            [
                'name' => 'Vitamin D3 1000IU',
                'category_id' => 3, // Supplements
                'supplier_id' => 3,
                'stock' => 200,
                'low_stock_threshold' => 50,
                'price' => 8.99,
                'expiry_date' => '2026-03-10',
                'description' => 'Vitamin D supplement'
            ],
            [
                'name' => 'Ibuprofen 400mg',
                'category_id' => 1, // Pain Relief
                'supplier_id' => 1,
                'stock' => 120,
                'low_stock_threshold' => 80,
                'price' => 7.25,
                'expiry_date' => '2025-09-30',
                'description' => 'Anti-inflammatory pain reliever'
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        // Create Sales (matching Figma design data)
        $salesData = [
            [
                'customer_id' => 1, // John Smith
                'user_id' => 1,
                'items' => [
                    ['product_id' => 1, 'quantity' => 2], // Paracetamol 500mg x2
                ],
                'date' => '2024-01-15'
            ],
            [
                'customer_id' => 2, // Sarah Johnson
                'user_id' => 1,
                'items' => [
                    ['product_id' => 3, 'quantity' => 1], // Vitamin D3 1000IU x1
                ],
                'date' => '2024-01-15'
            ],
            [
                'customer_id' => 3, // Mike Wilson
                'user_id' => 1,
                'items' => [
                    ['product_id' => 2, 'quantity' => 1], // Amoxicillin 250mg x1
                ],
                'date' => '2024-01-14'
            ],
        ];

        foreach ($salesData as $saleData) {
            $total = 0;
            $items = $saleData['items'];

            // Calculate total
            foreach ($items as $item) {
                $product = Product::find($item['product_id']);
                $total += $product->price * $item['quantity'];
            }

            // Create sale
            $sale = Sale::create([
                'customer_id' => $saleData['customer_id'],
                'user_id' => $saleData['user_id'],
                'total' => $total,
                'created_at' => $saleData['date'],
                'updated_at' => $saleData['date'],
            ]);

            // Create sale items
            foreach ($items as $item) {
                $product = Product::find($item['product_id']);

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $product->price * $item['quantity'],
                ]);
            }
        }

        $this->command->info('✅ App data seeded successfully!');
    }
}
