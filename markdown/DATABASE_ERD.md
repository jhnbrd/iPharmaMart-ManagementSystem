# iPharma Mart Management System - Entity Relationship Diagram

## Mermaid ERD (Copy and paste to mermaid.live)

```mermaid
erDiagram
    USERS ||--o{ SALES : creates
    USERS ||--o{ STOCK_MOVEMENTS : processes
    USERS ||--o{ SHELF_MOVEMENTS : processes
    USERS ||--o{ AUDIT_LOGS : generates
    CUSTOMERS ||--o{ SALES : "places orders"
    CATEGORIES ||--o{ PRODUCTS : categorizes
    SUPPLIERS ||--o{ PRODUCTS : supplies
    PRODUCTS ||--o{ SALE_ITEMS : "included in"
    PRODUCTS ||--o{ STOCK_MOVEMENTS : tracks
    PRODUCTS ||--o{ PRODUCT_BATCHES : "has batches"
    PRODUCTS ||--o{ SHELF_MOVEMENTS : "shelf restocking"
    PRODUCT_BATCHES ||--o{ STOCK_MOVEMENTS : "batch movements"
    PRODUCT_BATCHES ||--o{ SHELF_MOVEMENTS : "batch restock"
    SALES ||--o{ SALE_ITEMS : contains
    SALES ||--o{ SENIOR_CITIZEN_TRANSACTIONS : "SC discounts"
    SALES ||--o{ PWD_TRANSACTIONS : "PWD discounts"

    USERS {
        bigint id PK
        string name
        string username UK
        string email UK
        string password
        enum role "superadmin, admin, cashier, inventory_manager"
        timestamp created_at
        timestamp updated_at
    }

    CUSTOMERS {
        bigint id PK
        string name
        string email UK "nullable"
        string phone "nullable"
        string address "nullable"
        timestamp created_at
        timestamp updated_at
    }

    CATEGORIES {
        bigint id PK
        string name UK
        string description "nullable"
        timestamp created_at
        timestamp updated_at
    }

    SUPPLIERS {
        bigint id PK
        string name
        string contact_person "nullable, NEW"
        string email UK "nullable"
        string phone "nullable"
        string address "nullable"
        timestamp created_at
        timestamp updated_at
    }

    PRODUCTS {
        bigint id PK
        string name
        enum product_type "pharmacy, mini_mart"
        string barcode UK "nullable"
        bigint category_id FK
        bigint supplier_id FK
        decimal price "10,2"
        int shelf_stock "NEW - Stock on shelf"
        int back_stock "NEW - Stock in back"
        int low_stock_threshold
        int stock_danger_level
        string unit
        int unit_quantity
        timestamp created_at
        timestamp updated_at
    }

    SALES {
        bigint id PK
        bigint customer_id FK "nullable"
        bigint user_id FK
        decimal total "10,2"
        enum payment_method "cash, gcash, card, NEW"
        string reference_number "nullable, NEW"
        decimal paid_amount "10,2, NEW"
        decimal change_amount "10,2, NEW"
        timestamp created_at
        timestamp updated_at
    }

    SALE_ITEMS {
        bigint id PK
        bigint sale_id FK
        bigint product_id FK
        int quantity
        decimal price "10,2"
        decimal subtotal "10,2"
        timestamp created_at
        timestamp updated_at
    }

    STOCK_MOVEMENTS {
        bigint id PK
        bigint product_id FK
        bigint user_id FK
        bigint batch_id FK "nullable, NEW"
        enum type "in, out"
        int stock_in "NEW - Quantity added"
        int stock_out "NEW - Quantity removed"
        int previous_stock
        int new_stock
        string reference_number "nullable"
        text reason "renamed from remarks"
        timestamp created_at
        timestamp updated_at
    }

    PRODUCT_BATCHES {
        bigint id PK
        bigint product_id FK
        string batch_number UK "NEW"
        int quantity "NEW"
        int shelf_quantity "NEW"
        int back_quantity "NEW"
        date expiry_date "nullable, NEW"
        date manufacture_date "nullable, NEW"
        string supplier_invoice "nullable, NEW"
        text notes "nullable, NEW"
        timestamp created_at
        timestamp updated_at
    }

    SHELF_MOVEMENTS {
        bigint id PK
        bigint product_id FK "NEW"
        bigint batch_id FK "nullable, NEW"
        bigint user_id FK "NEW"
        int quantity "NEW"
        int previous_shelf_stock "NEW"
        int new_shelf_stock "NEW"
        int previous_back_stock "NEW"
        int new_back_stock "NEW"
        text remarks "nullable, NEW"
        timestamp created_at
        timestamp updated_at
    }

    SENIOR_CITIZEN_TRANSACTIONS {
        bigint id PK
        bigint sale_id FK "nullable, NEW"
        string sc_id_number "NEW"
        string sc_name "NEW"
        date sc_birthdate "NEW"
        decimal original_amount "10,2, NEW"
        decimal discount_amount "10,2, NEW"
        decimal final_amount "10,2, NEW"
        decimal discount_percentage "5,2, NEW"
        int items_purchased "NEW"
        timestamp created_at
        timestamp updated_at
    }

    PWD_TRANSACTIONS {
        bigint id PK
        bigint sale_id FK "nullable, NEW"
        string pwd_id_number "NEW"
        string pwd_name "NEW"
        date pwd_birthdate "NEW"
        string disability_type "NEW"
        decimal original_amount "10,2, NEW"
        decimal discount_amount "10,2, NEW"
        decimal final_amount "10,2, NEW"
        decimal discount_percentage "5,2, NEW"
        int items_purchased "NEW"
        timestamp created_at
        timestamp updated_at
    }

    AUDIT_LOGS {
        bigint id PK
        bigint user_id FK "nullable"
        string action
        string description
        string model_type "nullable"
        bigint model_id "nullable"
        json old_values "nullable"
        json new_values "nullable"
        string ip_address "nullable"
        timestamp created_at
        timestamp updated_at
    }
```

## Alternative: Detailed ERD with Relationships

```mermaid
erDiagram
    USERS {
        bigint id PK "Primary Key"
        string name "Full name of user"
        string username "Unique username"
        string email "Unique email address"
        string password "Hashed password"
        enum role "superadmin|admin|cashier|inventory_manager"
        timestamp email_verified_at
        timestamp created_at
        timestamp updated_at
    }

    CUSTOMERS {
        bigint id PK
        string name "Customer full name"
        string email "Nullable, Unique"
        string phone "Contact number"
        string address "Delivery address"
        timestamp created_at
        timestamp updated_at
    }

    CATEGORIES {
        bigint id PK
        string name "Unique category name"
        string description "Category description"
        timestamp created_at
        timestamp updated_at
    }

    SUPPLIERS {
        bigint id PK
        string name "Supplier/Vendor name"
        string contact_person "Contact person name"
        string email "Nullable, Unique"
        string phone "Supplier phone"
        string address "Supplier address"
        timestamp created_at
        timestamp updated_at
    }

    PRODUCTS {
        bigint id PK
        string name "Product name"
        enum product_type "pharmacy|mini_mart"
        string barcode "Nullable, Unique"
        bigint category_id FK "References categories.id"
        bigint supplier_id FK "References suppliers.id"
        decimal price "Selling price (10,2)"
        int shelf_stock "Stock on display shelf"
        int back_stock "Stock in back storage"
        int low_stock_threshold "Warning level"
        int stock_danger_level "Critical level"
        string unit "e.g., pcs, box, bottle"
        int unit_quantity "Items per unit"
        timestamp created_at
        timestamp updated_at
    }

    PRODUCT_BATCHES {
        bigint id PK
        bigint product_id FK "References products.id"
        string batch_number "Unique batch identifier"
        int quantity "Total batch quantity"
        int shelf_quantity "Quantity on shelf"
        int back_quantity "Quantity in back"
        date expiry_date "Batch expiration date"
        date manufacture_date "Manufacturing date"
        string supplier_invoice "Supplier reference"
        text notes "Additional notes"
        timestamp created_at
        timestamp updated_at
    }

    SHELF_MOVEMENTS {
        bigint id PK
        bigint product_id FK "References products.id"
        bigint batch_id FK "References product_batches.id, Nullable"
        bigint user_id FK "References users.id"
        int quantity "Quantity moved to shelf"
        int previous_shelf_stock "Shelf stock before"
        int new_shelf_stock "Shelf stock after"
        int previous_back_stock "Back stock before"
        int new_back_stock "Back stock after"
        text remarks "Movement notes"
        timestamp created_at
        timestamp updated_at
    }

    SALES {
        bigint id PK
        bigint customer_id FK "References customers.id, Nullable"
        bigint user_id FK "References users.id (cashier)"
        decimal total "Total amount including VAT (10,2)"
        enum payment_method "cash|gcash|card"
        string reference_number "For GCash/Card transactions"
        decimal paid_amount "Amount paid by customer (10,2)"
        decimal change_amount "Change returned (10,2)"
        timestamp created_at "Transaction datetime"
        timestamp updated_at
    }

    SALE_ITEMS {
        bigint id PK
        bigint sale_id FK "References sales.id"
        bigint product_id FK "References products.id"
        int quantity "Quantity sold"
        decimal price "Price at time of sale (10,2)"
        decimal subtotal "quantity * price (10,2)"
        timestamp created_at
        timestamp updated_at
    }

    STOCK_MOVEMENTS {
        bigint id PK
        bigint product_id FK "References products.id"
        bigint batch_id FK "References product_batches.id, Nullable"
        bigint user_id FK "References users.id"
        enum type "in|out"
        int stock_in "Quantity added (green display)"
        int stock_out "Quantity removed (red display)"
        int previous_stock "Stock before movement"
        int new_stock "Stock after movement"
        string reference_number "PO/Invoice number"
        text reason "Reason for movement"
        timestamp created_at "Movement datetime"
        timestamp updated_at
    }

    SENIOR_CITIZEN_TRANSACTIONS {
        bigint id PK
        bigint sale_id FK "References sales.id, Nullable"
        string sc_id_number "Senior Citizen ID"
        string sc_name "Senior Citizen name"
        date sc_birthdate "Birthdate"
        decimal original_amount "Pre-discount amount (10,2)"
        decimal discount_amount "Discount given (10,2)"
        decimal final_amount "Post-discount amount (10,2)"
        decimal discount_percentage "Discount rate (5,2)"
        int items_purchased "Number of items"
        timestamp created_at
        timestamp updated_at
    }

    PWD_TRANSACTIONS {
        bigint id PK
        bigint sale_id FK "References sales.id, Nullable"
        string pwd_id_number "PWD ID number"
        string pwd_name "PWD name"
        date pwd_birthdate "Birthdate"
        string disability_type "Type of disability"
        decimal original_amount "Pre-discount amount (10,2)"
        decimal discount_amount "Discount given (10,2)"
        decimal final_amount "Post-discount amount (10,2)"
        decimal discount_percentage "Discount rate (5,2)"
        int items_purchased "Number of items"
        timestamp created_at
        timestamp updated_at
    }

    AUDIT_LOGS {
        bigint id PK
        bigint user_id FK "References users.id, Nullable"
        string action "create|update|delete|login|logout|sale|stock_in|stock_out"
        string description "Human-readable description"
        string model_type "Polymorphic: Product, Sale, etc."
        bigint model_id "Polymorphic: Model ID"
        json old_values "Before state (for updates)"
        json new_values "After state (for creates/updates)"
        string ip_address "User IP address"
        timestamp created_at "Log timestamp"
        timestamp updated_at
    }

    %% Relationships with Cardinality
    USERS ||--o{ SALES : "creates (1:N)"
    USERS ||--o{ STOCK_MOVEMENTS : "processes (1:N)"
    USERS ||--o{ SHELF_MOVEMENTS : "processes (1:N)"
    USERS ||--o{ AUDIT_LOGS : "generates (1:N)"

    CUSTOMERS ||--o{ SALES : "places (1:N)"

    CATEGORIES ||--o{ PRODUCTS : "categorizes (1:N)"

    SUPPLIERS ||--o{ PRODUCTS : "supplies (1:N)"

    PRODUCTS ||--o{ SALE_ITEMS : "sold in (1:N)"
    PRODUCTS ||--o{ STOCK_MOVEMENTS : "tracked by (1:N)"
    PRODUCTS ||--o{ PRODUCT_BATCHES : "has batches (1:N)"
    PRODUCTS ||--o{ SHELF_MOVEMENTS : "restocking (1:N)"

    PRODUCT_BATCHES ||--o{ STOCK_MOVEMENTS : "movements (1:N)"
    PRODUCT_BATCHES ||--o{ SHELF_MOVEMENTS : "restock (1:N)"

    SALES ||--o{ SALE_ITEMS : "contains (1:N)"
    SALES ||--o{ SENIOR_CITIZEN_TRANSACTIONS : "SC discount (1:N)"
    SALES ||--o{ PWD_TRANSACTIONS : "PWD discount (1:N)"
```

## Simplified Class Diagram Style

```mermaid
classDiagram
    class User {
        +bigint id
        +string name
        +string username
        +string email
        +string password
        +enum role
        +timestamp created_at
        +createSale()
        +processStock()
        +login()
        +logout()
    }

    class Customer {
        +bigint id
        +string name
        +string email
        +string phone
        +string address
        +timestamp created_at
        +getSalesCount()
        +getTotalSpent()
    }

    class Category {
        +bigint id
        +string name
        +string description
        +timestamp created_at
        +getProductCount()
    }

    class Supplier {
        +bigint id
        +string name
        +string contact_person
        +string email
        +string phone
        +string address
        +timestamp created_at
        +getProductCount()
    }

    class Product {
        +bigint id
        +string name
        +enum product_type
        +string barcode
        +decimal price
        +int stock
        +int low_stock_threshold
        +int stock_danger_level
        +string unit
        +int unit_quantity
        +timestamp created_at
        +isLowStock()
        +isCriticalStock()
        +updateStock()
    }

    class Sale {
        +bigint id
        +decimal total
        +enum payment_method
        +string reference_number
        +decimal paid_amount
        +decimal change_amount
        +timestamp created_at
        +calculateTotal()
        +calculateVAT()
        +generateReceipt()
    }

    class SaleItem {
        +bigint id
        +int quantity
        +decimal price
        +decimal subtotal
        +timestamp created_at
        +calculateSubtotal()
    }

    class StockMovement {
        +bigint id
        +enum type
        +int quantity
        +int previous_stock
        +int new_stock
        +string reference_number
        +text remarks
        +timestamp created_at
        +processMovement()
    }

    class AuditLog {
        +bigint id
        +string action
        +string description
        +string model_type
        +bigint model_id
        +json old_values
        +json new_values
        +string ip_address
        +timestamp created_at
        +logActivity()
    }

    User "1" --> "N" Sale : creates
    User "1" --> "N" StockMovement : processes
    User "1" --> "N" AuditLog : generates
    Customer "1" --> "N" Sale : places
    Category "1" --> "N" Product : categorizes
    Supplier "1" --> "N" Product : supplies
    Product "1" --> "N" SaleItem : sold_in
    Product "1" --> "N" StockMovement : tracked_by
    Sale "1" --> "N" SaleItem : contains
```

## Database Statistics

### Table Count: 13 Tables

1. users
2. customers
3. categories
4. suppliers
5. products
6. sales
7. sale_items
8. stock_movements
9. audit_logs
10. **product_batches** (NEW - Batch tracking with expiry dates)
11. **shelf_movements** (NEW - Shelf restocking history)
12. **senior_citizen_transactions** (NEW - SC discount compliance)
13. **pwd_transactions** (NEW - PWD discount compliance)

### Key Relationships

-   **1:N Relationships**: 17 relationships
    -   Users → Sales (cashier)
    -   Users → Stock Movements (processor)
    -   Users → Shelf Movements (processor)
    -   Users → Audit Logs (actor)
    -   Customers → Sales
    -   Categories → Products
    -   Suppliers → Products
    -   Products → Sale Items
    -   Products → Stock Movements
    -   Products → Product Batches
    -   Products → Shelf Movements
    -   Product Batches → Stock Movements
    -   Product Batches → Shelf Movements
    -   Sales → Sale Items
    -   Sales → Senior Citizen Transactions
    -   Sales → PWD Transactions
-   **Polymorphic Relationship**: 1 (audit_logs with model_type/model_id)

### Recent Schema Changes

#### November 26, 2025 - Inventory Batch Tracking & Stock Separation

1. ✅ **Products Table - Stock Management Restructure**:

    - Removed `stock` column (single stock count)
    - Removed `expiry_date` column (moved to batch level)
    - Added `shelf_stock` (integer, default 0) - Stock on display shelf
    - Added `back_stock` (integer, default 0) - Stock in back storage
    - **New computed attribute**: `total_stock = shelf_stock + back_stock`

2. ✅ **Created product_batches Table** - Batch-Level Expiry Tracking:

    - `id` (bigint, PK)
    - `product_id` (bigint, FK → products.id, cascade)
    - `batch_number` (string, unique) - Format: `BATCH-{UNIQID}`
    - `quantity` (integer) - Total batch quantity
    - `shelf_quantity` (integer, default 0) - Quantity on shelf
    - `back_quantity` (integer, default 0) - Quantity in back
    - `expiry_date` (date, nullable) - Batch expiration date
    - `manufacture_date` (date, nullable) - Manufacturing date
    - `supplier_invoice` (string, nullable) - Supplier reference
    - `notes` (text, nullable)
    - Timestamps: created_at, updated_at
    - **Relationships**: belongs to Product, has many StockMovements/ShelfMovements

3. ✅ **Created shelf_movements Table** - Shelf Restocking Tracking:

    - `id` (bigint, PK)
    - `product_id` (bigint, FK → products.id, cascade)
    - `batch_id` (bigint, FK → product_batches.id, nullable, cascade)
    - `user_id` (bigint, FK → users.id)
    - `quantity` (integer) - Quantity moved to shelf
    - `previous_shelf_stock` (integer) - Shelf stock before
    - `new_shelf_stock` (integer) - Shelf stock after
    - `previous_back_stock` (integer) - Back stock before
    - `new_back_stock` (integer) - Back stock after
    - `remarks` (text, nullable)
    - Timestamps: created_at, updated_at
    - **Relationships**: belongs to Product, Batch, User

4. ✅ **Stock_Movements Table - Restructured**:

    - Removed `quantity` column (replaced with separate in/out)
    - Renamed `remarks` to `reason`
    - Added `stock_in` (integer, default 0) - Stock added
    - Added `stock_out` (integer, default 0) - Stock removed
    - Added `batch_id` (bigint, FK → product_batches.id, nullable, cascade)
    - **Display Logic**: Green color for stock_in, Red color for stock_out
    - **FIFO**: Stock out operations deduct from oldest batches first

5. ✅ **Created senior_citizen_transactions Table** - Philippine SC Compliance:

    - `id` (bigint, PK)
    - `sale_id` (bigint, FK → sales.id, nullable, cascade)
    - `sc_id_number` (string) - Senior Citizen ID
    - `sc_name` (string) - Senior Citizen name
    - `sc_birthdate` (date) - Birthdate
    - `original_amount` (decimal 10,2) - Pre-discount amount
    - `discount_amount` (decimal 10,2) - Discount given
    - `final_amount` (decimal 10,2) - Post-discount amount
    - `discount_percentage` (decimal 5,2, default 20.00) - Discount rate
    - `items_purchased` (integer, default 0) - Item count
    - Timestamps: created_at, updated_at
    - **Purpose**: Separate audit trail for SC discounts per PH regulations

6. ✅ **Created pwd_transactions Table** - Philippine PWD Compliance:
    - `id` (bigint, PK)
    - `sale_id` (bigint, FK → sales.id, nullable, cascade)
    - `pwd_id_number` (string) - PWD ID
    - `pwd_name` (string) - PWD name
    - `pwd_birthdate` (date) - Birthdate
    - `disability_type` (string) - Type of disability
    - `original_amount` (decimal 10,2)
    - `discount_amount` (decimal 10,2)
    - `final_amount` (decimal 10,2)
    - `discount_percentage` (decimal 5,2, default 20.00)
    - `items_purchased` (integer, default 0)
    - Timestamps: created_at, updated_at
    - **Purpose**: Separate PWD discount records for compliance

#### November 19, 2025 - Payment Method Enhancement

1. ✅ Added `contact_person` to suppliers table
2. ✅ Added payment tracking fields to sales table:
    - `payment_method` (enum: cash, gcash, card)
    - `reference_number` (string, nullable)
    - `paid_amount` (decimal 10,2)
    - `change_amount` (decimal 10,2)

### Data Types Summary

-   **Primary Keys**: All BIGINT AUTO_INCREMENT
-   **Foreign Keys**: All BIGINT with cascade on delete
-   **Decimals**: price, total, subtotal (10,2 precision)
-   **Enums**: role, product_type, payment_method, type (stock movement), action (audit)
-   **JSON**: old_values, new_values (audit logs)
-   **Timestamps**: created_at, updated_at (all tables)

### Indexes

-   Primary Keys: All tables
-   Unique Indexes: username, email (users, customers, suppliers), barcode (products), name (categories)
-   Foreign Key Indexes: All FK columns automatically indexed
