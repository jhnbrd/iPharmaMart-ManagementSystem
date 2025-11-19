# iPharma Mart Management System - Entity Relationship Diagram

## Mermaid ERD (Copy and paste to mermaid.live)

```mermaid
erDiagram
    USERS ||--o{ SALES : creates
    USERS ||--o{ STOCK_MOVEMENTS : processes
    USERS ||--o{ AUDIT_LOGS : generates
    CUSTOMERS ||--o{ SALES : "places orders"
    CATEGORIES ||--o{ PRODUCTS : categorizes
    SUPPLIERS ||--o{ PRODUCTS : supplies
    PRODUCTS ||--o{ SALE_ITEMS : "included in"
    PRODUCTS ||--o{ STOCK_MOVEMENTS : tracks
    SALES ||--o{ SALE_ITEMS : contains

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
        int stock
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
        enum type "in, out"
        int quantity
        int previous_stock
        int new_stock
        string reference_number "nullable"
        text remarks "nullable"
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
        int stock "Current stock quantity"
        int low_stock_threshold "Warning level"
        int stock_danger_level "Critical level"
        string unit "e.g., pcs, box, bottle"
        int unit_quantity "Items per unit"
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
        bigint user_id FK "References users.id"
        enum type "in|out"
        int quantity "Amount moved"
        int previous_stock "Stock before movement"
        int new_stock "Stock after movement"
        string reference_number "PO/Invoice number"
        text remarks "Additional notes"
        timestamp created_at "Movement datetime"
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
    USERS ||--o{ AUDIT_LOGS : "generates (1:N)"

    CUSTOMERS ||--o{ SALES : "places (1:N)"

    CATEGORIES ||--o{ PRODUCTS : "categorizes (1:N)"

    SUPPLIERS ||--o{ PRODUCTS : "supplies (1:N)"

    PRODUCTS ||--o{ SALE_ITEMS : "sold in (1:N)"
    PRODUCTS ||--o{ STOCK_MOVEMENTS : "tracked by (1:N)"

    SALES ||--o{ SALE_ITEMS : "contains (1:N)"
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

### Table Count: 9 Tables

1. users
2. customers
3. categories
4. suppliers
5. products
6. sales
7. sale_items
8. stock_movements
9. audit_logs

### Key Relationships

-   **1:N Relationships**: 9 relationships
-   **Polymorphic Relationship**: 1 (audit_logs with model_type/model_id)

### Recent Schema Changes (Nov 19, 2025)

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
