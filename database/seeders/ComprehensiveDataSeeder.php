<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockMovement;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ComprehensiveDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create Users
        $users = [
            [
                'name' => 'Super Admin',
                'username' => 'superadmin',
                'email' => 'superadmin@ipharmamart.com',
                'password' => Hash::make('password'),
                'role' => 'superadmin',
            ],
            [
                'name' => 'Admin User',
                'username' => 'admin',
                'email' => 'admin@ipharmamart.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ],
            [
                'name' => 'John Cashier',
                'username' => 'cashier1',
                'email' => 'cashier1@ipharmamart.com',
                'password' => Hash::make('password'),
                'role' => 'cashier',
            ],
            [
                'name' => 'Maria Cashier',
                'username' => 'cashier2',
                'email' => 'cashier2@ipharmamart.com',
                'password' => Hash::make('password'),
                'role' => 'cashier',
            ],
            [
                'name' => 'Pedro Inventory',
                'username' => 'inventory1',
                'email' => 'inventory@ipharmamart.com',
                'password' => Hash::make('password'),
                'role' => 'inventory_manager',
            ],
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }

        // Create Categories
        $categories = [
            ['name' => 'Pain Relief', 'description' => 'Analgesics and pain management'],
            ['name' => 'Antibiotics', 'description' => 'Antibacterial medications'],
            ['name' => 'Vitamins', 'description' => 'Vitamin and mineral supplements'],
            ['name' => 'Cold & Flu', 'description' => 'Cold, flu and cough medicines'],
            ['name' => 'Digestive Health', 'description' => 'Digestive and stomach medications'],
            ['name' => 'Skin Care', 'description' => 'Topical treatments and skin products'],
            ['name' => 'First Aid', 'description' => 'First aid supplies and antiseptics'],
            ['name' => 'Personal Care', 'description' => 'Personal hygiene products'],
            ['name' => 'Beverages', 'description' => 'Drinks and refreshments'],
            ['name' => 'Snacks', 'description' => 'Snacks and light meals'],
            ['name' => 'Household', 'description' => 'Household cleaning and supplies'],
            ['name' => 'Baby Care', 'description' => 'Baby products and infant care'],
        ];

        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }

        // Create Suppliers
        $suppliers = [
            ['name' => 'Unilab Pharma Inc.', 'phone' => '02-8123-4567', 'email' => 'orders@unilab.ph', 'address' => '66 United St., Mandaluyong City'],
            ['name' => 'Astrazeneca Philippines', 'phone' => '02-8234-5678', 'email' => 'supply@astrazeneca.ph', 'address' => 'Makati Avenue, Makati City'],
            ['name' => 'Pfizer Philippines', 'phone' => '02-8345-6789', 'email' => 'orders@pfizer.ph', 'address' => 'BGC, Taguig City'],
            ['name' => 'GlaxoSmithKline', 'phone' => '02-8456-7890', 'email' => 'supply@gsk.ph', 'address' => 'Alabang, Muntinlupa City'],
            ['name' => 'Pascual Laboratories', 'phone' => '02-8567-8901', 'email' => 'orders@pascual.ph', 'address' => 'Quezon City'],
            ['name' => 'Nestle Philippines', 'phone' => '02-8678-9012', 'email' => 'supply@nestle.ph', 'address' => 'Cabuyao, Laguna'],
            ['name' => 'Unilever Philippines', 'phone' => '02-8789-0123', 'email' => 'orders@unilever.ph', 'address' => 'Bonifacio Global City'],
            ['name' => 'Procter & Gamble PH', 'phone' => '02-8890-1234', 'email' => 'supply@pg.ph', 'address' => 'Makati City'],
            ['name' => 'Johnson & Johnson PH', 'phone' => '02-8901-2345', 'email' => 'orders@jnj.ph', 'address' => 'Pasig City'],
            ['name' => 'Wyeth Pharmaceuticals', 'phone' => '02-8012-3456', 'email' => 'supply@wyeth.ph', 'address' => 'Mandaluyong City'],
        ];

        foreach ($suppliers as $supplierData) {
            Supplier::create($supplierData);
        }

        // Create Products (50 products)
        $products = [
            // Pharmacy Products
            ['name' => 'Biogesic 500mg', 'product_type' => 'pharmacy', 'barcode' => 'BIO500-001', 'category_id' => 1, 'supplier_id' => 1, 'stock' => 500, 'low_stock_threshold' => 100, 'stock_danger_level' => 50, 'unit' => 'box', 'unit_quantity' => 100, 'price' => 285.00, 'expiry_date' => '2026-12-31', 'description' => 'Paracetamol pain reliever'],
            ['name' => 'Neozep Forte', 'product_type' => 'pharmacy', 'barcode' => 'NEO-FORTE-001', 'category_id' => 4, 'supplier_id' => 1, 'stock' => 450, 'low_stock_threshold' => 100, 'stock_danger_level' => 40, 'unit' => 'box', 'unit_quantity' => 100, 'price' => 350.00, 'expiry_date' => '2026-11-30', 'description' => 'Cold and flu relief'],
            ['name' => 'Amoxicillin 500mg', 'product_type' => 'pharmacy', 'barcode' => 'AMOX500-001', 'category_id' => 2, 'supplier_id' => 2, 'stock' => 300, 'low_stock_threshold' => 80, 'stock_danger_level' => 30, 'unit' => 'bottle', 'unit_quantity' => 100, 'price' => 450.00, 'expiry_date' => '2026-10-31', 'description' => 'Antibiotic capsules'],
            ['name' => 'Cetirizine 10mg', 'product_type' => 'pharmacy', 'barcode' => 'CET10-001', 'category_id' => 4, 'supplier_id' => 3, 'stock' => 600, 'low_stock_threshold' => 120, 'stock_danger_level' => 60, 'unit' => 'box', 'unit_quantity' => 100, 'price' => 280.00, 'expiry_date' => '2027-01-31', 'description' => 'Antihistamine for allergies'],
            ['name' => 'Vitamin C 500mg', 'product_type' => 'pharmacy', 'barcode' => 'VITC500-001', 'category_id' => 3, 'supplier_id' => 1, 'stock' => 800, 'low_stock_threshold' => 150, 'stock_danger_level' => 75, 'unit' => 'bottle', 'unit_quantity' => 100, 'price' => 195.00, 'expiry_date' => '2027-03-31', 'description' => 'Ascorbic acid supplement'],
            ['name' => 'Multivitamins + Minerals', 'product_type' => 'pharmacy', 'barcode' => 'MULTI-001', 'category_id' => 3, 'supplier_id' => 4, 'stock' => 400, 'low_stock_threshold' => 100, 'stock_danger_level' => 50, 'unit' => 'bottle', 'unit_quantity' => 100, 'price' => 425.00, 'expiry_date' => '2027-02-28', 'description' => 'Complete multivitamin formula'],
            ['name' => 'Ibuprofen 400mg', 'product_type' => 'pharmacy', 'barcode' => 'IBU400-001', 'category_id' => 1, 'supplier_id' => 2, 'stock' => 350, 'low_stock_threshold' => 90, 'stock_danger_level' => 45, 'unit' => 'box', 'unit_quantity' => 100, 'price' => 320.00, 'expiry_date' => '2026-09-30', 'description' => 'Anti-inflammatory pain reliever'],
            ['name' => 'Salbutamol Inhaler', 'product_type' => 'pharmacy', 'barcode' => 'SALB-INH-001', 'category_id' => 4, 'supplier_id' => 2, 'stock' => 120, 'low_stock_threshold' => 30, 'stock_danger_level' => 15, 'unit' => 'pcs', 'unit_quantity' => 1, 'price' => 285.00, 'expiry_date' => '2026-08-31', 'description' => 'Asthma relief inhaler'],
            ['name' => 'Omeprazole 20mg', 'product_type' => 'pharmacy', 'barcode' => 'OME20-001', 'category_id' => 5, 'supplier_id' => 3, 'stock' => 280, 'low_stock_threshold' => 70, 'stock_danger_level' => 35, 'unit' => 'box', 'unit_quantity' => 100, 'price' => 380.00, 'expiry_date' => '2026-12-31', 'description' => 'Acid reflux medication'],
            ['name' => 'Loperamide 2mg', 'product_type' => 'pharmacy', 'barcode' => 'LOP2-001', 'category_id' => 5, 'supplier_id' => 1, 'stock' => 250, 'low_stock_threshold' => 60, 'stock_danger_level' => 30, 'unit' => 'box', 'unit_quantity' => 100, 'price' => 195.00, 'expiry_date' => '2027-04-30', 'description' => 'Anti-diarrheal'],
            ['name' => 'Betadine Solution 120ml', 'product_type' => 'pharmacy', 'barcode' => 'BET120-001', 'category_id' => 7, 'supplier_id' => 9, 'stock' => 180, 'low_stock_threshold' => 50, 'stock_danger_level' => 25, 'unit' => 'bottle', 'unit_quantity' => 1, 'price' => 145.00, 'expiry_date' => '2027-06-30', 'description' => 'Antiseptic solution'],
            ['name' => 'Alcohol 70% 500ml', 'product_type' => 'pharmacy', 'barcode' => 'ALC500-001', 'category_id' => 7, 'supplier_id' => 5, 'stock' => 600, 'low_stock_threshold' => 150, 'stock_danger_level' => 75, 'unit' => 'bottle', 'unit_quantity' => 1, 'price' => 65.00, 'expiry_date' => '2028-12-31', 'description' => 'Isopropyl alcohol'],
            ['name' => 'Cotton Balls 100g', 'product_type' => 'pharmacy', 'barcode' => 'COT100-001', 'category_id' => 7, 'supplier_id' => 9, 'stock' => 220, 'low_stock_threshold' => 60, 'stock_danger_level' => 30, 'unit' => 'pack', 'unit_quantity' => 1, 'price' => 45.00, 'expiry_date' => null, 'description' => 'Absorbent cotton'],
            ['name' => 'Band-Aid Strips 100pcs', 'product_type' => 'pharmacy', 'barcode' => 'BND100-001', 'category_id' => 7, 'supplier_id' => 9, 'stock' => 150, 'low_stock_threshold' => 40, 'stock_danger_level' => 20, 'unit' => 'box', 'unit_quantity' => 100, 'price' => 125.00, 'expiry_date' => null, 'description' => 'Adhesive bandages'],
            ['name' => 'Hydrocortisone Cream', 'product_type' => 'pharmacy', 'barcode' => 'HYD-CRM-001', 'category_id' => 6, 'supplier_id' => 4, 'stock' => 160, 'low_stock_threshold' => 45, 'stock_danger_level' => 22, 'unit' => 'tube', 'unit_quantity' => 1, 'price' => 185.00, 'expiry_date' => '2026-07-31', 'description' => 'Anti-itch cream'],
            ['name' => 'Clotrimazole Cream', 'product_type' => 'pharmacy', 'barcode' => 'CLO-CRM-001', 'category_id' => 6, 'supplier_id' => 4, 'stock' => 140, 'low_stock_threshold' => 40, 'stock_danger_level' => 20, 'unit' => 'tube', 'unit_quantity' => 1, 'price' => 165.00, 'expiry_date' => '2026-08-31', 'description' => 'Antifungal cream'],
            ['name' => 'Loratadine 10mg', 'product_type' => 'pharmacy', 'barcode' => 'LOR10-001', 'category_id' => 4, 'supplier_id' => 3, 'stock' => 320, 'low_stock_threshold' => 80, 'stock_danger_level' => 40, 'unit' => 'box', 'unit_quantity' => 100, 'price' => 295.00, 'expiry_date' => '2027-05-31', 'description' => 'Non-drowsy antihistamine'],
            ['name' => 'Mefenamic Acid 500mg', 'product_type' => 'pharmacy', 'barcode' => 'MEF500-001', 'category_id' => 1, 'supplier_id' => 1, 'stock' => 380, 'low_stock_threshold' => 90, 'stock_danger_level' => 45, 'unit' => 'box', 'unit_quantity' => 100, 'price' => 325.00, 'expiry_date' => '2026-11-30', 'description' => 'Pain and fever reliever'],
            ['name' => 'Phenylephrine + CPM', 'product_type' => 'pharmacy', 'barcode' => 'PHE-CPM-001', 'category_id' => 4, 'supplier_id' => 1, 'stock' => 290, 'low_stock_threshold' => 75, 'stock_danger_level' => 37, 'unit' => 'box', 'unit_quantity' => 100, 'price' => 315.00, 'expiry_date' => '2027-01-31', 'description' => 'Decongestant'],
            ['name' => 'Ranitidine 150mg', 'product_type' => 'pharmacy', 'barcode' => 'RAN150-001', 'category_id' => 5, 'supplier_id' => 2, 'stock' => 210, 'low_stock_threshold' => 60, 'stock_danger_level' => 30, 'unit' => 'box', 'unit_quantity' => 100, 'price' => 265.00, 'expiry_date' => '2026-10-31', 'description' => 'Antacid medication'],

            // Mini Mart Products
            ['name' => 'Coca-Cola 1.5L', 'product_type' => 'mini_mart', 'barcode' => 'COKE15-001', 'category_id' => 9, 'supplier_id' => 6, 'stock' => 480, 'low_stock_threshold' => 100, 'stock_danger_level' => 50, 'unit' => 'bottle', 'unit_quantity' => 1, 'price' => 75.00, 'expiry_date' => '2026-06-30', 'description' => 'Soft drink'],
            ['name' => 'Sprite 1.5L', 'product_type' => 'mini_mart', 'barcode' => 'SPR15-001', 'category_id' => 9, 'supplier_id' => 6, 'stock' => 420, 'low_stock_threshold' => 100, 'stock_danger_level' => 50, 'unit' => 'bottle', 'unit_quantity' => 1, 'price' => 75.00, 'expiry_date' => '2026-06-30', 'description' => 'Lemon-lime soda'],
            ['name' => 'Mineral Water 500ml', 'product_type' => 'mini_mart', 'barcode' => 'WATER500-001', 'category_id' => 9, 'supplier_id' => 6, 'stock' => 960, 'low_stock_threshold' => 200, 'stock_danger_level' => 100, 'unit' => 'bottle', 'unit_quantity' => 1, 'price' => 18.00, 'expiry_date' => '2027-12-31', 'description' => 'Purified drinking water'],
            ['name' => 'Milo Drink 1L', 'product_type' => 'mini_mart', 'barcode' => 'MILO1L-001', 'category_id' => 9, 'supplier_id' => 6, 'stock' => 320, 'low_stock_threshold' => 80, 'stock_danger_level' => 40, 'unit' => 'pack', 'unit_quantity' => 1, 'price' => 95.00, 'expiry_date' => '2026-08-31', 'description' => 'Chocolate malt drink'],
            ['name' => 'Nescafe 3-in-1', 'product_type' => 'mini_mart', 'barcode' => 'NES3IN1-001', 'category_id' => 9, 'supplier_id' => 6, 'stock' => 550, 'low_stock_threshold' => 120, 'stock_danger_level' => 60, 'unit' => 'box', 'unit_quantity' => 30, 'price' => 185.00, 'expiry_date' => '2027-03-31', 'description' => 'Instant coffee mix'],
            ['name' => 'Piattos Cheese 85g', 'product_type' => 'mini_mart', 'barcode' => 'PIA85-001', 'category_id' => 10, 'supplier_id' => 7, 'stock' => 380, 'low_stock_threshold' => 90, 'stock_danger_level' => 45, 'unit' => 'pack', 'unit_quantity' => 1, 'price' => 45.00, 'expiry_date' => '2026-09-30', 'description' => 'Potato chips'],
            ['name' => 'Nova Barbecue 78g', 'product_type' => 'mini_mart', 'barcode' => 'NOVA78-001', 'category_id' => 10, 'supplier_id' => 7, 'stock' => 350, 'low_stock_threshold' => 85, 'stock_danger_level' => 42, 'unit' => 'pack', 'unit_quantity' => 1, 'price' => 38.00, 'expiry_date' => '2026-10-31', 'description' => 'Corn chips'],
            ['name' => 'Skyflakes Crackers 250g', 'product_type' => 'mini_mart', 'barcode' => 'SKY250-001', 'category_id' => 10, 'supplier_id' => 7, 'stock' => 280, 'low_stock_threshold' => 70, 'stock_danger_level' => 35, 'unit' => 'pack', 'unit_quantity' => 1, 'price' => 52.00, 'expiry_date' => '2027-02-28', 'description' => 'Soda crackers'],
            ['name' => 'Oishi Prawn Crackers', 'product_type' => 'mini_mart', 'barcode' => 'OISHI-PRW-001', 'category_id' => 10, 'supplier_id' => 7, 'stock' => 410, 'low_stock_threshold' => 100, 'stock_danger_level' => 50, 'unit' => 'pack', 'unit_quantity' => 1, 'price' => 42.00, 'expiry_date' => '2026-11-30', 'description' => 'Prawn flavored crackers'],
            ['name' => 'Lucky Me Instant Noodles', 'product_type' => 'mini_mart', 'barcode' => 'LUCKY-NOOD-001', 'category_id' => 10, 'supplier_id' => 7, 'stock' => 620, 'low_stock_threshold' => 150, 'stock_danger_level' => 75, 'unit' => 'pack', 'unit_quantity' => 1, 'price' => 15.00, 'expiry_date' => '2027-01-31', 'description' => 'Instant pancit canton'],
            ['name' => 'Safeguard Soap 135g', 'product_type' => 'mini_mart', 'barcode' => 'SAFE135-001', 'category_id' => 8, 'supplier_id' => 8, 'stock' => 340, 'low_stock_threshold' => 80, 'stock_danger_level' => 40, 'unit' => 'pcs', 'unit_quantity' => 1, 'price' => 32.00, 'expiry_date' => '2028-12-31', 'description' => 'Antibacterial soap'],
            ['name' => 'Palmolive Shampoo 340ml', 'product_type' => 'mini_mart', 'barcode' => 'PALM340-001', 'category_id' => 8, 'supplier_id' => 7, 'stock' => 280, 'low_stock_threshold' => 70, 'stock_danger_level' => 35, 'unit' => 'bottle', 'unit_quantity' => 1, 'price' => 125.00, 'expiry_date' => '2027-08-31', 'description' => 'Hair shampoo'],
            ['name' => 'Colgate Toothpaste 150g', 'product_type' => 'mini_mart', 'barcode' => 'COL150-001', 'category_id' => 8, 'supplier_id' => 7, 'stock' => 390, 'low_stock_threshold' => 90, 'stock_danger_level' => 45, 'unit' => 'pcs', 'unit_quantity' => 1, 'price' => 68.00, 'expiry_date' => '2027-10-31', 'description' => 'Toothpaste'],
            ['name' => 'Tide Detergent 1kg', 'product_type' => 'mini_mart', 'barcode' => 'TIDE1KG-001', 'category_id' => 11, 'supplier_id' => 8, 'stock' => 250, 'low_stock_threshold' => 60, 'stock_danger_level' => 30, 'unit' => 'pack', 'unit_quantity' => 1, 'price' => 185.00, 'expiry_date' => null, 'description' => 'Laundry detergent powder'],
            ['name' => 'Zonrox Bleach 1L', 'product_type' => 'mini_mart', 'barcode' => 'ZON1L-001', 'category_id' => 11, 'supplier_id' => 7, 'stock' => 180, 'low_stock_threshold' => 50, 'stock_danger_level' => 25, 'unit' => 'bottle', 'unit_quantity' => 1, 'price' => 48.00, 'expiry_date' => '2027-06-30', 'description' => 'Chlorine bleach'],
            ['name' => 'Downy Fabric Conditioner 1L', 'product_type' => 'mini_mart', 'barcode' => 'DOW1L-001', 'category_id' => 11, 'supplier_id' => 8, 'stock' => 220, 'low_stock_threshold' => 60, 'stock_danger_level' => 30, 'unit' => 'bottle', 'unit_quantity' => 1, 'price' => 155.00, 'expiry_date' => null, 'description' => 'Fabric softener'],
            ['name' => 'Pampers Diapers Size M', 'product_type' => 'mini_mart', 'barcode' => 'PAM-M-001', 'category_id' => 12, 'supplier_id' => 8, 'stock' => 140, 'low_stock_threshold' => 40, 'stock_danger_level' => 20, 'unit' => 'pack', 'unit_quantity' => 36, 'price' => 385.00, 'expiry_date' => null, 'description' => 'Baby diapers medium size'],
            ['name' => 'Johnson Baby Powder 200g', 'product_type' => 'mini_mart', 'barcode' => 'JOH200-001', 'category_id' => 12, 'supplier_id' => 9, 'stock' => 190, 'low_stock_threshold' => 50, 'stock_danger_level' => 25, 'unit' => 'bottle', 'unit_quantity' => 1, 'price' => 125.00, 'expiry_date' => '2027-12-31', 'description' => 'Baby powder'],
            ['name' => 'Baby Wipes 80 Pulls', 'product_type' => 'mini_mart', 'barcode' => 'WIPES80-001', 'category_id' => 12, 'supplier_id' => 9, 'stock' => 260, 'low_stock_threshold' => 65, 'stock_danger_level' => 32, 'unit' => 'pack', 'unit_quantity' => 1, 'price' => 95.00, 'expiry_date' => '2027-09-30', 'description' => 'Wet wipes for babies'],
            ['name' => 'Alaska Evaporated Milk', 'product_type' => 'mini_mart', 'barcode' => 'ALASK-EVAP-001', 'category_id' => 9, 'supplier_id' => 6, 'stock' => 310, 'low_stock_threshold' => 80, 'stock_danger_level' => 40, 'unit' => 'can', 'unit_quantity' => 1, 'price' => 42.00, 'expiry_date' => '2026-12-31', 'description' => 'Evaporated milk'],
            ['name' => 'Eden Cheese 165g', 'product_type' => 'mini_mart', 'barcode' => 'EDEN165-001', 'category_id' => 10, 'supplier_id' => 7, 'stock' => 180, 'low_stock_threshold' => 50, 'stock_danger_level' => 25, 'unit' => 'pcs', 'unit_quantity' => 1, 'price' => 68.00, 'expiry_date' => '2026-11-30', 'description' => 'Processed cheese'],
            ['name' => 'Del Monte Tomato Sauce', 'product_type' => 'mini_mart', 'barcode' => 'DEL-TOM-001', 'category_id' => 10, 'supplier_id' => 7, 'stock' => 240, 'low_stock_threshold' => 60, 'stock_danger_level' => 30, 'unit' => 'pack', 'unit_quantity' => 1, 'price' => 28.00, 'expiry_date' => '2027-04-30', 'description' => 'Tomato sauce'],
            ['name' => 'Lysol Disinfectant Spray', 'product_type' => 'mini_mart', 'barcode' => 'LYS-SPRAY-001', 'category_id' => 11, 'supplier_id' => 8, 'stock' => 150, 'low_stock_threshold' => 40, 'stock_danger_level' => 20, 'unit' => 'can', 'unit_quantity' => 1, 'price' => 285.00, 'expiry_date' => '2027-12-31', 'description' => 'Disinfectant spray'],
            ['name' => 'Baygon Spray 600ml', 'product_type' => 'mini_mart', 'barcode' => 'BAY600-001', 'category_id' => 11, 'supplier_id' => 8, 'stock' => 170, 'low_stock_threshold' => 45, 'stock_danger_level' => 22, 'unit' => 'can', 'unit_quantity' => 1, 'price' => 165.00, 'expiry_date' => '2028-03-31', 'description' => 'Insect killer spray'],
            ['name' => 'Mr. Clean All Purpose', 'product_type' => 'mini_mart', 'barcode' => 'MRC-ALL-001', 'category_id' => 11, 'supplier_id' => 8, 'stock' => 200, 'low_stock_threshold' => 55, 'stock_danger_level' => 27, 'unit' => 'bottle', 'unit_quantity' => 1, 'price' => 95.00, 'expiry_date' => null, 'description' => 'All-purpose cleaner'],
            ['name' => 'Red Bull Energy Drink', 'product_type' => 'mini_mart', 'barcode' => 'RED-BULL-001', 'category_id' => 9, 'supplier_id' => 6, 'stock' => 380, 'low_stock_threshold' => 90, 'stock_danger_level' => 45, 'unit' => 'can', 'unit_quantity' => 1, 'price' => 65.00, 'expiry_date' => '2026-10-31', 'description' => 'Energy drink'],
            ['name' => 'Gatorade 500ml', 'product_type' => 'mini_mart', 'barcode' => 'GAT500-001', 'category_id' => 9, 'supplier_id' => 6, 'stock' => 450, 'low_stock_threshold' => 100, 'stock_danger_level' => 50, 'unit' => 'bottle', 'unit_quantity' => 1, 'price' => 42.00, 'expiry_date' => '2026-12-31', 'description' => 'Sports drink'],
            ['name' => 'Kopiko Coffee Candy', 'product_type' => 'mini_mart', 'barcode' => 'KOP-CANDY-001', 'category_id' => 10, 'supplier_id' => 7, 'stock' => 520, 'low_stock_threshold' => 120, 'stock_danger_level' => 60, 'unit' => 'pack', 'unit_quantity' => 1, 'price' => 28.00, 'expiry_date' => '2027-06-30', 'description' => 'Coffee candy'],
            ['name' => 'Mentos Candy Roll', 'product_type' => 'mini_mart', 'barcode' => 'MEN-ROLL-001', 'category_id' => 10, 'supplier_id' => 7, 'stock' => 480, 'low_stock_threshold' => 110, 'stock_danger_level' => 55, 'unit' => 'pcs', 'unit_quantity' => 1, 'price' => 22.00, 'expiry_date' => '2027-08-31', 'description' => 'Chewy mint candy'],
            ['name' => 'Cadbury Chocolate Bar', 'product_type' => 'mini_mart', 'barcode' => 'CAD-CHOC-001', 'category_id' => 10, 'supplier_id' => 6, 'stock' => 320, 'low_stock_threshold' => 80, 'stock_danger_level' => 40, 'unit' => 'pcs', 'unit_quantity' => 1, 'price' => 58.00, 'expiry_date' => '2026-09-30', 'description' => 'Milk chocolate'],
        ];

        foreach ($products as $productData) {
            Product::create($productData);
        }

        // Create Customers (35 customers)
        $customers = [
            ['name' => 'Juan Dela Cruz', 'phone' => '09171234567', 'email' => 'juan.dc@email.com', 'address' => 'Manila City'],
            ['name' => 'Maria Santos', 'phone' => '09181234568', 'email' => 'maria.s@email.com', 'address' => 'Quezon City'],
            ['name' => 'Pedro Reyes', 'phone' => '09191234569', 'email' => 'pedro.r@email.com', 'address' => 'Makati City'],
            ['name' => 'Ana Garcia', 'phone' => '09201234570', 'email' => 'ana.g@email.com', 'address' => 'Pasig City'],
            ['name' => 'Jose Ramos', 'phone' => '09211234571', 'email' => 'jose.r@email.com', 'address' => 'Mandaluyong City'],
            ['name' => 'Linda Cruz', 'phone' => '09221234572', 'email' => 'linda.c@email.com', 'address' => 'Taguig City'],
            ['name' => 'Robert Tan', 'phone' => '09231234573', 'email' => 'robert.t@email.com', 'address' => 'Paranaque City'],
            ['name' => 'Sofia Mendoza', 'phone' => '09241234574', 'email' => 'sofia.m@email.com', 'address' => 'Las Pinas City'],
            ['name' => 'Carlos Lopez', 'phone' => '09251234575', 'email' => 'carlos.l@email.com', 'address' => 'Muntinlupa City'],
            ['name' => 'Diana Bautista', 'phone' => '09261234576', 'email' => 'diana.b@email.com', 'address' => 'Caloocan City'],
            ['name' => 'Miguel Fernandez', 'phone' => '09271234577', 'email' => 'miguel.f@email.com', 'address' => 'Valenzuela City'],
            ['name' => 'Elena Pascual', 'phone' => '09281234578', 'email' => 'elena.p@email.com', 'address' => 'Malabon City'],
            ['name' => 'Rico Villanueva', 'phone' => '09291234579', 'email' => 'rico.v@email.com', 'address' => 'Navotas City'],
            ['name' => 'Grace Santiago', 'phone' => '09301234580', 'email' => 'grace.s@email.com', 'address' => 'San Juan City'],
            ['name' => 'Antonio Morales', 'phone' => '09311234581', 'email' => 'antonio.m@email.com', 'address' => 'Pasay City'],
            ['name' => 'Beatriz Aquino', 'phone' => '09321234582', 'email' => 'beatriz.a@email.com', 'address' => 'Marikina City'],
            ['name' => 'Ferdinand Diaz', 'phone' => '09331234583', 'email' => 'ferdinand.d@email.com', 'address' => 'Pateros'],
            ['name' => 'Gloria Castillo', 'phone' => '09341234584', 'email' => 'gloria.c@email.com', 'address' => 'Manila City'],
            ['name' => 'Henry Torres', 'phone' => '09351234585', 'email' => 'henry.t@email.com', 'address' => 'Quezon City'],
            ['name' => 'Isabel Rojas', 'phone' => '09361234586', 'email' => 'isabel.r@email.com', 'address' => 'Makati City'],
            ['name' => 'Jacob Flores', 'phone' => '09371234587', 'email' => 'jacob.f@email.com', 'address' => 'Pasig City'],
            ['name' => 'Katherine Gonzales', 'phone' => '09381234588', 'email' => 'katherine.g@email.com', 'address' => 'Taguig City'],
            ['name' => 'Leonardo Rivera', 'phone' => '09391234589', 'email' => 'leonardo.r@email.com', 'address' => 'Paranaque City'],
            ['name' => 'Monica Valdez', 'phone' => '09401234590', 'email' => 'monica.v@email.com', 'address' => 'Las Pinas City'],
            ['name' => 'Nathan Perez', 'phone' => '09411234591', 'email' => 'nathan.p@email.com', 'address' => 'Muntinlupa City'],
            ['name' => 'Olivia Ramirez', 'phone' => '09421234592', 'email' => 'olivia.r@email.com', 'address' => 'Caloocan City'],
            ['name' => 'Patrick Cruz', 'phone' => '09431234593', 'email' => 'patrick.c@email.com', 'address' => 'Valenzuela City'],
            ['name' => 'Queen Salazar', 'phone' => '09441234594', 'email' => 'queen.s@email.com', 'address' => 'Malabon City'],
            ['name' => 'Ramon Gutierrez', 'phone' => '09451234595', 'email' => 'ramon.g@email.com', 'address' => 'Navotas City'],
            ['name' => 'Sandra Ortega', 'phone' => '09461234596', 'email' => 'sandra.o@email.com', 'address' => 'San Juan City'],
            ['name' => 'Teodoro Navarro', 'phone' => '09471234597', 'email' => 'teodoro.n@email.com', 'address' => 'Pasay City'],
            ['name' => 'Ursula Mendez', 'phone' => '09481234598', 'email' => 'ursula.m@email.com', 'address' => 'Marikina City'],
            ['name' => 'Victor Serrano', 'phone' => '09491234599', 'email' => 'victor.s@email.com', 'address' => 'Pateros'],
            ['name' => 'Wendy Castro', 'phone' => '09501234600', 'email' => 'wendy.c@email.com', 'address' => 'Manila City'],
            ['name' => 'Xavier Domingo', 'phone' => '09511234601', 'email' => 'xavier.d@email.com', 'address' => 'Quezon City'],
        ];

        foreach ($customers as $customerData) {
            Customer::create($customerData);
        }

        // Create Sales (40 sales - oldest first, starting 3 months ago)
        $startDate = Carbon::now()->subMonths(3);
        $salesData = [];

        for ($i = 1; $i <= 40; $i++) {
            $daysToAdd = ($i - 1) * 2; // Every 2 days
            $saleDate = $startDate->copy()->addDays($daysToAdd);

            $sale = Sale::create([
                'user_id' => rand(1, 5),
                'customer_id' => rand(1, 35),
                'total' => 0, // Will be calculated
                'created_at' => $saleDate,
                'updated_at' => $saleDate,
            ]);

            // Add 2-5 items per sale
            $itemCount = rand(2, 5);
            $saleTotal = 0;

            for ($j = 0; $j < $itemCount; $j++) {
                $product = Product::find(rand(1, 50));
                $quantity = rand(1, 5);
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

            // Update sale total
            $sale->update(['total' => $saleTotal]);
        }

        // Create Stock Movements (30 movements - oldest first)
        $stockDate = Carbon::now()->subMonths(2);

        for ($i = 1; $i <= 30; $i++) {
            $daysToAdd = ($i - 1) * 2;
            $movementDate = $stockDate->copy()->addDays($daysToAdd);

            $product = Product::find(rand(1, 50));
            $type = ['in', 'out'][rand(0, 1)];
            $quantity = rand(10, 100);
            $previousStock = $product->stock;

            if ($type === 'in') {
                $newStock = $previousStock + $quantity;
            } else {
                // Make sure we don't go negative
                $quantity = min($quantity, $previousStock);
                $newStock = $previousStock - $quantity;
            }

            StockMovement::create([
                'product_id' => $product->id,
                'user_id' => rand(1, 5),
                'type' => $type,
                'quantity' => $quantity,
                'previous_stock' => $previousStock,
                'new_stock' => $newStock,
                'reference_number' => $type === 'in' ? 'PO-' . str_pad($i, 5, '0', STR_PAD_LEFT) : 'OUT-' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'remarks' => $type === 'in' ? 'Stock replenishment' : 'Stock adjustment',
                'created_at' => $movementDate,
                'updated_at' => $movementDate,
            ]);

            $product->update(['stock' => $newStock]);
        }

        $this->command->info('✅ Comprehensive data seeded successfully!');
        $this->command->info('📊 Created:');
        $this->command->info('   - 5 Users');
        $this->command->info('   - 12 Categories');
        $this->command->info('   - 10 Suppliers');
        $this->command->info('   - 50 Products');
        $this->command->info('   - 35 Customers');
        $this->command->info('   - 40 Sales (oldest to newest)');
        $this->command->info('   - 30 Stock Movements (oldest to newest)');
    }
}
