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
use App\Models\AuditLog;
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

        // Create Categories (separated by product type)
        $this->command->info('📁 Creating categories...');

        // Pharmacy Categories (8)
        $pharmacyCategories = [
            'Pain Relief',
            'Antibiotics',
            'Vitamins & Supplements',
            'Cold & Flu',
            'Digestive Health',
            'Skin Care',
            'First Aid',
            'Personal Health'
        ];

        // Mini Mart Categories (8)
        $miniMartCategories = [
            'Beverages',
            'Snacks & Chips',
            'Instant Noodles',
            'Canned Goods',
            'Personal Care & Toiletries',
            'Household Cleaning',
            'Baby Products',
            'Dairy & Condiments'
        ];

        $allCategories = array_merge($pharmacyCategories, $miniMartCategories);
        foreach ($allCategories as $name) {
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
        $products = [
            // Pharmacy Products (25) - Generic names should be general terms, product names include dosage
            ['brand' => 'UNILAB', 'name' => 'Biogesic 500mg', 'generic' => 'Paracetamol', 'type' => 'pharmacy', 'price' => 65, 'category' => 1],
            ['brand' => 'UNILAB', 'name' => 'Dolfenal 500mg', 'generic' => 'Mefenamic Acid', 'type' => 'pharmacy', 'price' => 125, 'category' => 1],
            ['brand' => 'UNILAB', 'name' => 'Alaxan FR', 'generic' => 'Ibuprofen + Paracetamol', 'type' => 'pharmacy', 'price' => 85, 'category' => 1],
            ['brand' => 'UNILAB', 'name' => 'Medicol 200mg', 'generic' => 'Ibuprofen', 'type' => 'pharmacy', 'price' => 95, 'category' => 1],
            ['brand' => 'UNILAB', 'name' => 'Decolgen Forte', 'generic' => 'Phenylpropanolamine + Paracetamol', 'type' => 'pharmacy', 'price' => 78, 'category' => 4],
            ['brand' => 'UNILAB', 'name' => 'Bioflu', 'generic' => 'Phenylephrine + Paracetamol', 'type' => 'pharmacy', 'price' => 72, 'category' => 4],
            ['brand' => 'UNILAB', 'name' => 'Tuseran Forte', 'generic' => 'Dextromethorphan', 'type' => 'pharmacy', 'price' => 68, 'category' => 4],
            ['brand' => 'UNILAB', 'name' => 'Kremil-S', 'generic' => 'Aluminum Hydroxide + Magnesium Hydroxide', 'type' => 'pharmacy', 'price' => 55, 'category' => 5],
            ['brand' => 'RITEMED', 'name' => 'Paracetamol 500mg', 'generic' => 'Paracetamol', 'type' => 'pharmacy', 'price' => 35, 'category' => 1],
            ['brand' => 'RITEMED', 'name' => 'Ibuprofen 400mg', 'generic' => 'Ibuprofen', 'type' => 'pharmacy', 'price' => 45, 'category' => 1],
            ['brand' => 'RITEMED', 'name' => 'Cetirizine 10mg', 'generic' => 'Cetirizine', 'type' => 'pharmacy', 'price' => 38, 'category' => 4],
            ['brand' => 'RITEMED', 'name' => 'Amoxicillin 500mg', 'generic' => 'Amoxicillin', 'type' => 'pharmacy', 'price' => 42, 'category' => 2],
            ['brand' => 'RITEMED', 'name' => 'Loperamide 2mg', 'generic' => 'Loperamide', 'type' => 'pharmacy', 'price' => 32, 'category' => 5],
            ['brand' => 'MUNDIPHARMA', 'name' => 'Betadine Solution 30ml', 'generic' => 'Povidone-Iodine', 'type' => 'pharmacy', 'price' => 165, 'category' => 7],
            ['brand' => 'BAYER', 'name' => 'Aspirin 80mg', 'generic' => 'Aspirin', 'type' => 'pharmacy', 'price' => 58, 'category' => 1],
            ['brand' => 'AstraZeneca', 'name' => 'Losartan 50mg', 'generic' => 'Losartan', 'type' => 'pharmacy', 'price' => 155, 'category' => 8],
            ['brand' => 'PFIZER', 'name' => 'Norvasc 5mg', 'generic' => 'Amlodipine', 'type' => 'pharmacy', 'price' => 245, 'category' => 8],
            ['brand' => 'GSK', 'name' => 'Amoxicillin 500mg', 'generic' => 'Amoxicillin', 'type' => 'pharmacy', 'price' => 185, 'category' => 2],
            ['brand' => 'PASCUAL', 'name' => 'Salbutamol Inhaler', 'generic' => 'Salbutamol', 'type' => 'pharmacy', 'price' => 385, 'category' => 4],
            ['brand' => 'GREEN CROSS', 'name' => 'Isopropyl Alcohol 70% 500ml', 'generic' => 'Isopropyl Alcohol', 'type' => 'pharmacy', 'price' => 95, 'category' => 7],
            ['brand' => 'BAND-AID', 'name' => 'Adhesive Bandages 10s', 'generic' => 'Adhesive Bandages', 'type' => 'pharmacy', 'price' => 125, 'category' => 7],
            ['brand' => 'JOHNSON & JOHNSON', 'name' => 'Cotton Balls 50g', 'generic' => 'Absorbent Cotton', 'type' => 'pharmacy', 'price' => 85, 'category' => 7],
            ['brand' => 'VITAHEALTH', 'name' => 'Vitamin C 500mg', 'generic' => 'Ascorbic Acid', 'type' => 'pharmacy', 'price' => 145, 'category' => 3],
            ['brand' => 'CENTRUM', 'name' => 'Multivitamins 30s', 'generic' => 'Multivitamins', 'type' => 'pharmacy', 'price' => 385, 'category' => 3],
            ['brand' => 'BACTIDOL', 'name' => 'Oral Antiseptic 250ml', 'generic' => 'Hexetidine', 'type' => 'pharmacy', 'price' => 165, 'category' => 8],

            // Mini Mart Products (25) - Product names include brand and size
            ['brand' => 'Coca-Cola', 'name' => 'Coca-Cola 1.5L', 'generic' => null, 'type' => 'mini_mart', 'price' => 85, 'category' => 9],
            ['brand' => 'Sprite', 'name' => 'Sprite 1.5L', 'generic' => null, 'type' => 'mini_mart', 'price' => 85, 'category' => 9],
            ['brand' => 'Royal', 'name' => 'Royal Tru-Orange 1.5L', 'generic' => null, 'type' => 'mini_mart', 'price' => 75, 'category' => 9],
            ['brand' => 'Absolute', 'name' => 'Absolute Distilled Water 500ml', 'generic' => null, 'type' => 'mini_mart', 'price' => 15, 'category' => 9],
            ['brand' => 'Milo', 'name' => 'Milo Chocolate Drink 1L', 'generic' => null, 'type' => 'mini_mart', 'price' => 165, 'category' => 9],
            ['brand' => 'Nescafe', 'name' => 'Nescafe 3-in-1 Original 30s', 'generic' => null, 'type' => 'mini_mart', 'price' => 125, 'category' => 9],
            ['brand' => 'Great Taste', 'name' => 'Great Taste White Coffee 30s', 'generic' => null, 'type' => 'mini_mart', 'price' => 115, 'category' => 9],
            ['brand' => 'Piattos', 'name' => 'Piattos Cheese 85g', 'generic' => null, 'type' => 'mini_mart', 'price' => 45, 'category' => 10],
            ['brand' => 'Nova', 'name' => 'Nova Barbecue 78g', 'generic' => null, 'type' => 'mini_mart', 'price' => 42, 'category' => 10],
            ['brand' => 'Skyflakes', 'name' => 'Skyflakes Crackers 250g', 'generic' => null, 'type' => 'mini_mart', 'price' => 58, 'category' => 10],
            ['brand' => 'Oishi', 'name' => 'Oishi Prawn Crackers 60g', 'generic' => null, 'type' => 'mini_mart', 'price' => 38, 'category' => 10],
            ['brand' => 'Lucky Me', 'name' => 'Lucky Me Pancit Canton', 'generic' => null, 'type' => 'mini_mart', 'price' => 18, 'category' => 11],
            ['brand' => 'Safeguard', 'name' => 'Safeguard Soap Bar 135g', 'generic' => null, 'type' => 'mini_mart', 'price' => 35, 'category' => 13],
            ['brand' => 'Palmolive', 'name' => 'Palmolive Shampoo 180ml', 'generic' => null, 'type' => 'mini_mart', 'price' => 95, 'category' => 13],
            ['brand' => 'Colgate', 'name' => 'Colgate Toothpaste 150g', 'generic' => null, 'type' => 'mini_mart', 'price' => 125, 'category' => 13],
            ['brand' => 'Tide', 'name' => 'Tide Detergent Powder 1kg', 'generic' => null, 'type' => 'mini_mart', 'price' => 185, 'category' => 14],
            ['brand' => 'Zonrox', 'name' => 'Zonrox Bleach 1L', 'generic' => null, 'type' => 'mini_mart', 'price' => 65, 'category' => 14],
            ['brand' => 'Downy', 'name' => 'Downy Fabric Conditioner 1L', 'generic' => null, 'type' => 'mini_mart', 'price' => 145, 'category' => 14],
            ['brand' => 'Pampers', 'name' => 'Pampers Diapers Size M 16s', 'generic' => null, 'type' => 'mini_mart', 'price' => 385, 'category' => 15],
            ['brand' => 'Alaska', 'name' => 'Alaska Evaporated Milk 370ml', 'generic' => null, 'type' => 'mini_mart', 'price' => 58, 'category' => 16],
            ['brand' => 'Eden', 'name' => 'Eden Cheese 165g', 'generic' => null, 'type' => 'mini_mart', 'price' => 95, 'category' => 16],
            ['brand' => 'Del Monte', 'name' => 'Del Monte Tomato Sauce 250g', 'generic' => null, 'type' => 'mini_mart', 'price' => 42, 'category' => 12],
            ['brand' => 'Red Bull', 'name' => 'Red Bull Energy Drink 250ml', 'generic' => null, 'type' => 'mini_mart', 'price' => 75, 'category' => 9],
            ['brand' => 'Gatorade', 'name' => 'Gatorade Blue Bolt 500ml', 'generic' => null, 'type' => 'mini_mart', 'price' => 45, 'category' => 9],
            ['brand' => 'Kopiko', 'name' => 'Kopiko Coffee Candy 175g', 'generic' => null, 'type' => 'mini_mart', 'price' => 25, 'category' => 10],
        ];

        foreach ($products as $i => $productData) {
            // Use specified category or assign random based on product type
            $categoryId = isset($productData['category'])
                ? $productData['category']
                : ($productData['type'] === 'pharmacy' ? rand(1, 8) : rand(9, 16));

            Product::create([
                'brand_name' => $productData['brand'],
                'name' => $productData['name'],
                'generic_name' => $productData['generic'],
                'product_type' => $productData['type'],
                'barcode' => 'BAR' . str_pad($i + 1, 5, '0', STR_PAD_LEFT),
                'category_id' => $categoryId,
                'supplier_id' => rand(1, 10),
                'shelf_stock' => rand(50, 150),
                'back_stock' => rand(75, 200),
                'low_stock_threshold' => 80,
                'stock_danger_level' => 40,
                'unit' => $productData['type'] === 'pharmacy' ? 'box' : 'pcs',
                'unit_quantity' => 1,
                'price' => $productData['price'],
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
        $this->command->info('   ✓ Audit Logs: ' . AuditLog::count());
        $this->command->info('');
    }
}
