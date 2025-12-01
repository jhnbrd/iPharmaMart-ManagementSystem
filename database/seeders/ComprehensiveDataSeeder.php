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
     * Run the database seeds aligned with new ERD structure
     */
    public function run(): void
    {
        $this->command->info('🌱 Seeding database with comprehensive test data...');

        // Create 5 Users
        $this->command->info('👥 Creating users...');
        User::create(['name' => 'Super Admin', 'username' => 'superadmin', 'email' => 'superadmin@ipharmamart.com', 'password' => Hash::make('password'), 'role' => 'superadmin']);
        User::create(['name' => 'Admin User', 'username' => 'admin', 'email' => 'admin@ipharmamart.com', 'password' => Hash::make('password'), 'role' => 'admin']);
        User::create(['name' => 'John Cashier', 'username' => 'cashier1', 'email' => 'cashier1@ipharmamart.com', 'password' => Hash::make('password'), 'role' => 'cashier']);
        User::create(['name' => 'Maria Cashier', 'username' => 'cashier2', 'email' => 'cashier2@ipharmamart.com', 'password' => Hash::make('password'), 'role' => 'cashier']);
        User::create(['name' => 'Pedro Inventory', 'username' => 'inventory1', 'email' => 'inventory@ipharmamart.com', 'password' => Hash::make('password'), 'role' => 'inventory_manager']);

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

        // Create 40 Customers with realistic Filipino names
        $this->command->info('👤 Creating 40 customers...');
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
            $firstName = explode(' ', $name)[0];
            Customer::create([
                'name' => $name,
                'phone' => '09' . str_pad(171234567 + $i + 1, 9, '0', STR_PAD_LEFT),
                'email' => strtolower(str_replace(' ', '.', $name)) . '@gmail.com',
                'address' => ['Manila', 'Quezon City', 'Makati', 'Pasig'][rand(0, 3)] . ' City',
            ]);
        }

        // Create 55+ Sales with items and SC/PWD transactions
        $this->command->info('💰 Creating 55+ sales...');
        $startDate = Carbon::now()->subMonths(3);
        for ($i = 0; $i < 55; $i++) {
            $sale = Sale::create([
                'user_id' => rand(3, 4),  // Cashiers
                'customer_id' => rand(1, 40),
                'total' => 0,
                'payment_method' => ['cash', 'gcash', 'card'][rand(0, 2)],
                'paid_amount' => 0,
                'change_amount' => 0,
                'created_at' => $startDate->copy()->addDays(rand(0, 90)),
            ]);

            $saleTotal = 0;
            $itemCount = rand(2, 5);
            for ($j = 0; $j < $itemCount; $j++) {
                $product = Product::find(rand(1, 50));
                $quantity = rand(1, 3);
                $subtotal = $product->price * $quantity;
                $saleTotal += $subtotal;

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->price,
                    'subtotal' => $subtotal,
                ]);
            }

            // 20% get SC/PWD discount
            if (rand(1, 100) <= 20) {
                $isSC = rand(0, 1) === 0;
                $discountAmount = $saleTotal * 0.20;
                $finalAmount = $saleTotal - $discountAmount;

                if ($isSC) {
                    SeniorCitizenTransaction::create([
                        'sale_id' => $sale->id,
                        'sc_id_number' => 'SC-2024-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                        'sc_name' => 'Senior Citizen ' . $i,
                        'sc_birthdate' => Carbon::create(rand(1940, 1965), rand(1, 12), rand(1, 28)),
                        'original_amount' => $saleTotal,
                        'discount_amount' => $discountAmount,
                        'final_amount' => $finalAmount,
                        'discount_percentage' => 20,
                        'items_purchased' => $itemCount,
                    ]);
                } else {
                    PwdTransaction::create([
                        'sale_id' => $sale->id,
                        'pwd_id_number' => 'PWD-' . str_pad($i, 6, '0', STR_PAD_LEFT),
                        'pwd_name' => 'PWD Person ' . $i,
                        'pwd_birthdate' => Carbon::create(rand(1960, 2005), rand(1, 12), rand(1, 28)),
                        'disability_type' => ['Visual', 'Hearing', 'Physical', 'Mental'][rand(0, 3)] . ' Impairment',
                        'original_amount' => $saleTotal,
                        'discount_amount' => $discountAmount,
                        'final_amount' => $finalAmount,
                        'discount_percentage' => 20,
                        'items_purchased' => $itemCount,
                    ]);
                }
                $saleTotal = $finalAmount;
            }

            $paidAmount = ceil($saleTotal / 50) * 50;
            $sale->update(['total' => $saleTotal, 'paid_amount' => $paidAmount, 'change_amount' => $paidAmount - $saleTotal]);
        }

        // Create 45+ Stock Movements (with stock_in and stock_out)
        $this->command->info('📊 Creating 45+ stock movements...');
        $stockDate = Carbon::now()->subMonths(3);
        for ($i = 0; $i < 45; $i++) {
            $product = Product::with('batches')->find(rand(1, 50));
            $batch = $product->batches->first();
            $type = $i < 35 ? 'in' : 'out';
            $quantity = rand(20, 100);
            $prev = $product->shelf_stock + $product->back_stock;

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
                    'reference_number' => 'PO-' . str_pad($i + 1, 5, '0', STR_PAD_LEFT),
                    'reason' => 'Stock replenishment',
                    'created_at' => $stockDate->copy()->addDays(rand(0, 90)),
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
                    'reference_number' => 'OUT-' . str_pad($i + 1, 5, '0', STR_PAD_LEFT),
                    'reason' => 'Stock adjustment',
                    'created_at' => $stockDate->copy()->addDays(rand(0, 90)),
                ]);
            }
        }

        // Create 38+ Shelf Movements
        $this->command->info('🔄 Creating 38+ shelf movements...');
        $shelfDate = Carbon::now()->subMonths(2);
        for ($i = 0; $i < 38; $i++) {
            $product = Product::with('batches')->find(rand(1, 50));
            if ($product->back_stock > 10) {
                $quantity = rand(10, min(50, $product->back_stock));
                $batch = $product->batches->first();
                $prevShelf = $product->shelf_stock;
                $prevBack = $product->back_stock;

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
                    'created_at' => $shelfDate->copy()->addDays(rand(0, 60)),
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
