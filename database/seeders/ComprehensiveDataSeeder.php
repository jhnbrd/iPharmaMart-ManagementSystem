<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockMovement;
use App\Models\ShelfMovement;
use App\Models\SeniorCitizenTransaction;
use App\Models\PwdTransaction;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ComprehensiveDataSeeder extends Seeder
{
    /**
     * Run the database seeds with data from January 2025 to December 2025
     * Comprehensive test data for scalability and QA testing
     */
    public function run(): void
    {
        $this->command->info('🌱 Seeding database with comprehensive test data (Jan 2025 - Dec 2025)...');

        // Create 5 Users
        $this->command->info('👥 Creating users...');
        User::create(['name' => 'Super Admin', 'username' => 'superadmin', 'email' => 'superadmin@ipharmamart.com', 'password' => Hash::make('password'), 'role' => 'superadmin', 'created_at' => Carbon::create(2025, 1, 1)]);
        User::create(['name' => 'Admin User', 'username' => 'admin', 'email' => 'admin@ipharmamart.com', 'password' => Hash::make('password'), 'role' => 'admin', 'created_at' => Carbon::create(2025, 1, 1)]);
        User::create(['name' => 'John Cashier', 'username' => 'cashier1', 'email' => 'cashier1@ipharmamart.com', 'password' => Hash::make('password'), 'role' => 'cashier', 'created_at' => Carbon::create(2025, 1, 2)]);
        User::create(['name' => 'Maria Cashier', 'username' => 'cashier2', 'email' => 'cashier2@ipharmamart.com', 'password' => Hash::make('password'), 'role' => 'cashier', 'created_at' => Carbon::create(2025, 1, 2)]);
        User::create(['name' => 'Pedro Inventory', 'username' => 'inventory1', 'email' => 'inventory@ipharmamart.com', 'password' => Hash::make('password'), 'role' => 'inventory_manager', 'created_at' => Carbon::create(2025, 1, 2)]);

        // Create 12 Categories
        $this->command->info('📁 Creating categories...');
        $categories = ['Pain Relief', 'Antibiotics', 'Vitamins', 'Cold & Flu', 'Digestive Health', 'Skin Care', 'First Aid', 'Personal Care', 'Beverages', 'Snacks', 'Household', 'Baby Care'];
        foreach ($categories as $name) {
            Category::create(['name' => $name, 'description' => $name . ' products']);
        }

        // Create 10 Suppliers
        $this->command->info('🏢 Creating suppliers...');
        $suppliers = ['Unilab Pharma', 'Astrazeneca', 'Pfizer', 'GlaxoSmithKline', 'Pascual Labs', 'Nestle', 'Unilever', 'Procter & Gamble', 'Johnson & Johnson', 'Wyeth'];
        foreach ($suppliers as $i => $name) {
            Supplier::create([
                'name' => $name,
                'contact_person' => 'Contact ' . ($i + 1),
                'phone' => '02-812' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'email' => strtolower(str_replace(' ', '', $name)) . '@ph.com',
                'address' => 'Manila City',
            ]);
        }

        // Create 50 Products (shelf_stock + back_stock)
        $this->command->info('📦 Creating 50 products...');
        $productNames = [
            'Biogesic 500mg',
            'Neozep Forte',
            'Amoxicillin 500mg',
            'Cetirizine 10mg',
            'Vitamin C 500mg',
            'Multivitamins',
            'Ibuprofen 400mg',
            'Salbutamol Inhaler',
            'Omeprazole 20mg',
            'Loperamide 2mg',
            'Betadine Solution',
            'Alcohol 70% 500ml',
            'Cotton Balls 100g',
            'Band-Aid Strips',
            'Hydrocortisone Cream',
            'Clotrimazole Cream',
            'Loratadine 10mg',
            'Mefenamic Acid',
            'Phenylephrine',
            'Ranitidine 150mg',
            'Coca-Cola 1.5L',
            'Sprite 1.5L',
            'Mineral Water 500ml',
            'Milo Drink 1L',
            'Nescafe 3-in-1',
            'Piattos Cheese 85g',
            'Nova Barbecue 78g',
            'Skyflakes Crackers',
            'Oishi Prawn Crackers',
            'Lucky Me Noodles',
            'Safeguard Soap',
            'Palmolive Shampoo',
            'Colgate Toothpaste',
            'Tide Detergent 1kg',
            'Zonrox Bleach 1L',
            'Downy Fabric Conditioner',
            'Pampers Diapers Size M',
            'Johnson Baby Powder',
            'Baby Wipes 80 Pulls',
            'Alaska Evap Milk',
            'Eden Cheese 165g',
            'Del Monte Tomato Sauce',
            'Lysol Disinfectant',
            'Baygon Spray 600ml',
            'Mr. Clean All Purpose',
            'Red Bull Energy Drink',
            'Gatorade 500ml',
            'Kopiko Coffee Candy',
            'Mentos Candy Roll',
            'Cadbury Chocolate Bar'
        ];

        foreach ($productNames as $i => $name) {
            $isPharmacy = $i < 20;
            Product::create([
                'name' => $name,
                'product_type' => $isPharmacy ? 'pharmacy' : 'mini_mart',
                'barcode' => 'BAR' . str_pad($i + 1, 5, '0', STR_PAD_LEFT),
                'category_id' => rand(1, 12),
                'supplier_id' => rand(1, 10),
                'shelf_stock' => rand(50, 150),
                'back_stock' => rand(75, 200),
                'low_stock_threshold' => 80,
                'stock_danger_level' => 40,
                'unit' => $isPharmacy ? 'box' : 'pcs',
                'unit_quantity' => 1,
                'price' => $isPharmacy ? rand(150, 450) : rand(20, 200),
            ]);
        }

        // Create 100+ Product Batches (2 per product)
        $this->command->info('📦 Creating 100+ product batches...');
        foreach (Product::all() as $product) {
            for ($i = 0; $i < 2; $i++) {
                ProductBatch::create([
                    'product_id' => $product->id,
                    'batch_number' => 'BATCH-' . strtoupper(uniqid()),
                    'quantity' => $product->shelf_stock + $product->back_stock,
                    'shelf_quantity' => $product->shelf_stock,
                    'back_quantity' => $product->back_stock,
                    'expiry_date' => Carbon::now()->addMonths(rand(6, 24)),
                    'manufacture_date' => Carbon::now()->subMonths(rand(1, 6)),
                    'supplier_invoice' => 'INV-' . str_pad($i + 1, 5, '0', STR_PAD_LEFT),
                ]);
            }
        }

        // Create 40 Customers with realistic Filipino names + Senior Citizen & PWD fields
        $this->command->info('👤 Creating 40 customers (with SC/PWD data)...');
        $customerNames = [
            'Maria Santos',
            'Jose Reyes',
            'Ana Cruz',
            'Juan dela Cruz',
            'Carmen Garcia',
            'Pedro Gonzales',
            'Rosa Martinez',
            'Antonio Lopez',
            'Teresa Hernandez',
            'Roberto Flores',
            'Luz Perez',
            'Francisco Ramirez',
            'Josefa Torres',
            'Miguel Morales',
            'Esperanza Ramos',
            'Carlos Castillo',
            'Dolores Jimenez',
            'Manuel Rivera',
            'Remedios Gutierrez',
            'Alfredo Mendoza',
            'Concepcion Vargas',
            'Eduardo Silva',
            'Pilar Medina',
            'Rafael Castro',
            'Soledad Ortega',
            'Alejandro Romero',
            'Gloria Aguilar',
            'Fernando Navarro',
            'Amparo Diaz',
            'Arturo Herrera',
            'Milagros Ruiz',
            'Ricardo Moreno',
            'Cristina Jimenez',
            'Leonardo Vega',
            'Rosario Campos',
            'Enrique Contreras',
            'Natividad Guerrero',
            'Teodoro Mejia',
            'Encarnacion Rojas',
            'Salvador Sandoval'
        ];

        foreach ($customerNames as $i => $name) {
            // 30% are senior citizens, 20% are PWD, 50% are regular
            $isSenior = $i < 12; // First 12 are senior citizens
            $isPwd = $i >= 12 && $i < 20; // Next 8 are PWD

            Customer::create([
                'name' => $name,
                'phone' => '09' . str_pad(171234567 + $i + 1, 9, '0', STR_PAD_LEFT),
                'email' => strtolower(str_replace(' ', '.', $name)) . '@gmail.com',
                'address' => ['Manila', 'Quezon City', 'Makati', 'Pasig'][rand(0, 3)] . ' City',
                'is_senior_citizen' => $isSenior,
                'senior_citizen_id' => $isSenior ? 'SC-2025-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT) : null,
                'is_pwd' => $isPwd,
                'pwd_id' => $isPwd ? 'PWD-2025-' . str_pad($i + 1, 6, '0', STR_PAD_LEFT) : null,
                'created_at' => Carbon::create(2025, 1, rand(3, 31)),
            ]);
        }

        // Create 250+ Sales from January 2025 to December 2025 with SC/PWD transactions
        $this->command->info('💰 Creating 250+ sales (Jan-Dec 2025)...');
        $startDate = Carbon::create(2025, 1, 1);
        $endDate = Carbon::create(2025, 12, 5); // Today
        $totalDays = $startDate->diffInDays($endDate);

        // Create varied sales throughout the year (1-3 sales per day on average)
        for ($i = 0; $i < 280; $i++) {
            $randomDate = $startDate->copy()->addDays(rand(0, $totalDays));
            $customerId = rand(1, 40);
            $customer = Customer::find($customerId);

            $sale = Sale::create([
                'user_id' => rand(3, 4),  // Cashiers
                'customer_id' => $customerId,
                'total' => 0,
                'payment_method' => ['cash', 'gcash', 'card'][rand(0, 2)],
                'paid_amount' => 0,
                'change_amount' => 0,
                'created_at' => $randomDate,
                'updated_at' => $randomDate,
            ]);

            $saleTotal = 0;
            $itemCount = rand(2, 6);
            for ($j = 0; $j < $itemCount; $j++) {
                $product = Product::find(rand(1, 50));
                $quantity = rand(1, 4);
                $subtotal = $product->price * $quantity;
                $saleTotal += $subtotal;

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->price,
                    'subtotal' => $subtotal,
                    'created_at' => $randomDate,
                ]);
            }

            // Apply SC/PWD discount if customer has discount eligibility
            $hasDiscount = $customer->is_senior_citizen || $customer->is_pwd;

            if ($hasDiscount) {
                $discountAmount = $saleTotal * 0.20;
                $finalAmount = $saleTotal - $discountAmount;

                if ($customer->is_senior_citizen) {
                    SeniorCitizenTransaction::create([
                        'sale_id' => $sale->id,
                        'sc_id_number' => $customer->senior_citizen_id,
                        'sc_name' => $customer->name,
                        'sc_birthdate' => Carbon::create(rand(1940, 1965), rand(1, 12), rand(1, 28)),
                        'original_amount' => $saleTotal,
                        'discount_amount' => $discountAmount,
                        'final_amount' => $finalAmount,
                        'discount_percentage' => 20,
                        'items_purchased' => $itemCount,
                        'created_at' => $randomDate,
                    ]);
                } else if ($customer->is_pwd) {
                    PwdTransaction::create([
                        'sale_id' => $sale->id,
                        'pwd_id_number' => $customer->pwd_id,
                        'pwd_name' => $customer->name,
                        'pwd_birthdate' => Carbon::create(rand(1960, 2005), rand(1, 12), rand(1, 28)),
                        'disability_type' => ['Visual', 'Hearing', 'Physical', 'Mental'][rand(0, 3)] . ' Impairment',
                        'original_amount' => $saleTotal,
                        'discount_amount' => $discountAmount,
                        'final_amount' => $finalAmount,
                        'discount_percentage' => 20,
                        'items_purchased' => $itemCount,
                        'created_at' => $randomDate,
                    ]);
                }
                $saleTotal = $finalAmount;
            }

            $paidAmount = ceil($saleTotal / 50) * 50;
            $sale->update(['total' => $saleTotal, 'paid_amount' => $paidAmount, 'change_amount' => $paidAmount - $saleTotal]);
        }

        // Create 150+ Stock Movements (with stock_in and stock_out) from Jan-Dec 2025
        $this->command->info('📊 Creating 150+ stock movements (Jan-Dec 2025)...');
        $stockDate = Carbon::create(2025, 1, 5);

        for ($i = 0; $i < 160; $i++) {
            $product = Product::with('batches')->find(rand(1, 50));
            $batch = $product->batches->first();
            $type = $i < 120 ? 'in' : 'out'; // More stock-in than stock-out
            $quantity = rand(20, 150);
            $prev = $product->shelf_stock + $product->back_stock;
            $randomDate = $stockDate->copy()->addDays(rand(0, 335));

            if ($type === 'in') {
                $product->increment(rand(0, 1) === 0 ? 'shelf_stock' : 'back_stock', $quantity);
                StockMovement::create([
                    'product_id' => $product->id,
                    'batch_id' => $batch?->id,
                    'user_id' => 5,  // Inventory manager
                    'type' => 'in',
                    'stock_in' => $quantity,
                    'stock_out' => 0,
                    'previous_stock' => $prev,
                    'new_stock' => $prev + $quantity,
                    'reference_number' => 'PO-2025-' . str_pad($i + 1, 5, '0', STR_PAD_LEFT),
                    'reason' => 'Stock replenishment',
                    'created_at' => $randomDate,
                    'updated_at' => $randomDate,
                ]);
            } else {
                $qty = min($quantity, $prev);
                if ($product->shelf_stock > 0) {
                    $product->decrement('shelf_stock', min($qty, $product->shelf_stock));
                } else {
                    $product->decrement('back_stock', min($qty, $product->back_stock));
                }
                StockMovement::create([
                    'product_id' => $product->id,
                    'batch_id' => $batch?->id,
                    'user_id' => 5,
                    'type' => 'out',
                    'stock_in' => 0,
                    'stock_out' => $qty,
                    'previous_stock' => $prev,
                    'new_stock' => $prev - $qty,
                    'reference_number' => 'OUT-2025-' . str_pad($i + 1, 5, '0', STR_PAD_LEFT),
                    'reason' => 'Stock adjustment',
                    'created_at' => $randomDate,
                    'updated_at' => $randomDate,
                ]);
            }
        }

        // Create 80+ Shelf Movements from Jan-Dec 2025
        $this->command->info('🔄 Creating 80+ shelf movements (Jan-Dec 2025)...');
        $shelfDate = Carbon::create(2025, 1, 10);

        for ($i = 0; $i < 85; $i++) {
            $product = Product::with('batches')->find(rand(1, 50));
            if ($product->back_stock > 10) {
                $quantity = rand(10, min(60, $product->back_stock));
                $batch = $product->batches->first();
                $prevShelf = $product->shelf_stock;
                $prevBack = $product->back_stock;
                $randomDate = $shelfDate->copy()->addDays(rand(0, 330));

                $product->increment('shelf_stock', $quantity);
                $product->decrement('back_stock', $quantity);

                ShelfMovement::create([
                    'product_id' => $product->id,
                    'batch_id' => $batch?->id,
                    'user_id' => 5,
                    'quantity' => $quantity,
                    'previous_shelf_stock' => $prevShelf,
                    'new_shelf_stock' => $prevShelf + $quantity,
                    'previous_back_stock' => $prevBack,
                    'new_back_stock' => $prevBack - $quantity,
                    'remarks' => 'Restocking display shelf',
                    'created_at' => $randomDate,
                    'updated_at' => $randomDate,
                ]);
            }
        }

        $this->command->info('');
        $this->command->info('✅ Database seeded successfully!');
        $this->command->info('');
        $this->command->info('📊 Summary:');
        $this->command->info('   ✓ Users: ' . User::count());
        $this->command->info('   ✓ Categories: ' . Category::count());
        $this->command->info('   ✓ Suppliers: ' . Supplier::count());
        $this->command->info('   ✓ Products: ' . Product::count());
        $this->command->info('   ✓ Product Batches: ' . ProductBatch::count());
        $this->command->info('   ✓ Customers: ' . Customer::count());
        $this->command->info('   ✓ Sales: ' . Sale::count());
        $this->command->info('   ✓ Sale Items: ' . SaleItem::count());
        $this->command->info('   ✓ Stock Movements: ' . StockMovement::count());
        $this->command->info('   ✓ Shelf Movements: ' . ShelfMovement::count());
        $this->command->info('   ✓ Senior Citizen Transactions: ' . SeniorCitizenTransaction::count());
        $this->command->info('   ✓ PWD Transactions: ' . PwdTransaction::count());
        $this->command->info('');
    }
}
