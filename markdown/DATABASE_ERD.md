# Database ERD - iPharma Mart Management System

## Complete System ERD

```plantuml
@startuml CompleteERD

entity "users" as users {
  * id : bigint <<PK>>
  --
  * name : varchar
  * email : varchar <<unique>>
  * password : varchar
  * role : enum(admin, cashier)
  * created_at : timestamp
  * updated_at : timestamp
}

entity "customers" as customers {
  * id : bigint <<PK>>
  --
  * name : varchar
  email : varchar
  phone : varchar
  address : text
  birthdate : date
  disability_type : varchar
  * created_at : timestamp
  * updated_at : timestamp
}

entity "suppliers" as suppliers {
  * id : bigint <<PK>>
  --
  * name : varchar
  contact_person : varchar
  email : varchar
  phone : varchar
  address : text
  * created_at : timestamp
  * updated_at : timestamp
}

entity "categories" as categories {
  * id : bigint <<PK>>
  --
  * name : varchar <<unique>>
  description : text
  * created_at : timestamp
  * updated_at : timestamp
}

entity "products" as products {
  * id : bigint <<PK>>
  --
  * sku : varchar <<unique>>
  * name : varchar
  generic_name : varchar
  brand_name : varchar
  description : text
  * category_id : bigint <<FK>>
  * price : decimal(10,2)
  * cost : decimal(10,2)
  shelf_stock : integer
  back_stock : integer
  stock_danger_level : integer
  * supplier_id : bigint <<FK>>
  expiry_date : date
  * created_at : timestamp
  * updated_at : timestamp
}

entity "sales" as sales {
  * id : bigint <<PK>>
  --
  * customer_id : bigint <<FK>>
  * user_id : bigint <<FK>>
  * total : decimal(10,2)
  * payment_method : enum(cash, gcash, card)
  * paid_amount : decimal(10,2)
  * change_amount : decimal(10,2)
  reference_number : varchar
  * created_at : timestamp
  * updated_at : timestamp
}

entity "sale_items" as sale_items {
  * id : bigint <<PK>>
  --
  * sale_id : bigint <<FK>>
  * product_id : bigint <<FK>>
  * quantity : integer
  * price : decimal(10,2)
  * subtotal : decimal(10,2)
  * created_at : timestamp
  * updated_at : timestamp
}

entity "senior_citizen_transactions" as senior_citizen_transactions {
  * id : bigint <<PK>>
  --
  * sale_id : bigint <<FK>>
  * sc_id_number : varchar
  * sc_name : varchar
  * sc_birthdate : date
  * original_amount : decimal(10,2)
  * discount_amount : decimal(10,2)
  * final_amount : decimal(10,2)
  * discount_percentage : decimal(5,2)
  * items_purchased : text
  * created_at : timestamp
  * updated_at : timestamp
}

entity "pwd_transactions" as pwd_transactions {
  * id : bigint <<PK>>
  --
  * sale_id : bigint <<FK>>
  * pwd_id_number : varchar
  * pwd_name : varchar
  * pwd_birthdate : date
  disability_type : varchar
  * original_amount : decimal(10,2)
  * discount_amount : decimal(10,2)
  * final_amount : decimal(10,2)
  * discount_percentage : decimal(5,2)
  * items_purchased : text
  * created_at : timestamp
  * updated_at : timestamp
}

entity "stock_movements" as stock_movements {
  * id : bigint <<PK>>
  --
  * product_id : bigint <<FK>>
  * type : enum(in, out, restock, adjustment)
  * quantity : integer
  * stock_in_quantity : integer
  * stock_out_quantity : integer
  batch_id : varchar
  * remaining_stock : integer
  reference : varchar
  notes : text
  * user_id : bigint <<FK>>
  * created_at : timestamp
  * updated_at : timestamp
}

entity "shelf_movements" as shelf_movements {
  * id : bigint <<PK>>
  --
  * product_id : bigint <<FK>>
  * quantity : integer
  * movement_type : enum(to_shelf, to_back)
  * user_id : bigint <<FK>>
  notes : text
  * created_at : timestamp
  * updated_at : timestamp
}

entity "audit_logs" as audit_logs {
  * id : bigint <<PK>>
  --
  * user_id : bigint <<FK>>
  * action : varchar
  * description : text
  auditable_type : varchar
  auditable_id : bigint
  old_values : json
  new_values : json
  ip_address : varchar
  user_agent : text
  * created_at : timestamp
  * updated_at : timestamp
}

products }o--|| categories : category_id
products }o--|| suppliers : supplier_id
sales }o--|| customers : customer_id
sales }o--|| users : user_id
sale_items }o--|| sales : sale_id
sale_items }o--|| products : product_id
senior_citizen_transactions }o--|| sales : sale_id
pwd_transactions }o--|| sales : sale_id
stock_movements }o--|| products : product_id
stock_movements }o--|| users : user_id
shelf_movements }o--|| products : product_id
shelf_movements }o--|| users : user_id
audit_logs }o--|| users : user_id

@enduml
```

---

## Module-Based ERDs

### 1. User Management Module

```plantuml
@startuml UserManagement

entity "users" as users {
  * id : bigint <<PK>>
  --
  * name : varchar
  * email : varchar <<unique>>
  * password : varchar
  * role : enum(admin, cashier)
  * created_at : timestamp
  * updated_at : timestamp
}

@enduml
```

### 2. Customer & Supplier Module

```plantuml
@startuml CustomerSupplier

entity "customers" as customers {
  * id : bigint <<PK>>
  --
  * name : varchar
  email : varchar
  phone : varchar
  address : text
  birthdate : date
  disability_type : varchar
  * created_at : timestamp
  * updated_at : timestamp
}

entity "suppliers" as suppliers {
  * id : bigint <<PK>>
  --
  * name : varchar
  contact_person : varchar
  email : varchar
  phone : varchar
  address : text
  * created_at : timestamp
  * updated_at : timestamp
}

@enduml
```

### 3. Product & Inventory Module

```plantuml
@startuml ProductInventory

entity "categories" as categories {
  * id : bigint <<PK>>
  --
  * name : varchar <<unique>>
  description : text
  * created_at : timestamp
  * updated_at : timestamp
}

entity "products" as products {
  * id : bigint <<PK>>
  --
  * sku : varchar <<unique>>
  * name : varchar
  generic_name : varchar
  brand_name : varchar
  description : text
  * category_id : bigint <<FK>>
  * price : decimal(10,2)
  * cost : decimal(10,2)
  shelf_stock : integer
  back_stock : integer
  stock_danger_level : integer
  * supplier_id : bigint <<FK>>
  expiry_date : date
  * created_at : timestamp
  * updated_at : timestamp
}

entity "suppliers" as suppliers {
  * id : bigint <<PK>>
  --
  * name : varchar
  contact_person : varchar
  * created_at : timestamp
  * updated_at : timestamp
}

products }o--|| categories : category_id
products }o--|| suppliers : supplier_id

@enduml
```

### 4. Stock Management Module

```plantuml
@startuml StockManagement

entity "products" as products {
  * id : bigint <<PK>>
  --
  * sku : varchar <<unique>>
  * name : varchar
  shelf_stock : integer
  back_stock : integer
  * created_at : timestamp
  * updated_at : timestamp
}

entity "stock_movements" as stock_movements {
  * id : bigint <<PK>>
  --
  * product_id : bigint <<FK>>
  * type : enum(in, out, restock, adjustment)
  * quantity : integer
  * stock_in_quantity : integer
  * stock_out_quantity : integer
  batch_id : varchar
  * remaining_stock : integer
  reference : varchar
  notes : text
  * user_id : bigint <<FK>>
  * created_at : timestamp
  * updated_at : timestamp
}

entity "shelf_movements" as shelf_movements {
  * id : bigint <<PK>>
  --
  * product_id : bigint <<FK>>
  * quantity : integer
  * movement_type : enum(to_shelf, to_back)
  * user_id : bigint <<FK>>
  notes : text
  * created_at : timestamp
  * updated_at : timestamp
}

entity "users" as users {
  * id : bigint <<PK>>
  --
  * name : varchar
  * role : enum(admin, cashier)
}

stock_movements }o--|| products : product_id
stock_movements }o--|| users : user_id
shelf_movements }o--|| products : product_id
shelf_movements }o--|| users : user_id

@enduml
```

### 5. Sales & POS Module

```plantuml
@startuml SalesPOS

entity "customers" as customers {
  * id : bigint <<PK>>
  --
  * name : varchar
  phone : varchar
  * created_at : timestamp
  * updated_at : timestamp
}

entity "users" as users {
  * id : bigint <<PK>>
  --
  * name : varchar
  * role : enum(admin, cashier)
}

entity "sales" as sales {
  * id : bigint <<PK>>
  --
  * customer_id : bigint <<FK>>
  * user_id : bigint <<FK>>
  * total : decimal(10,2)
  * payment_method : enum(cash, gcash, card)
  * paid_amount : decimal(10,2)
  * change_amount : decimal(10,2)
  reference_number : varchar
  * created_at : timestamp
  * updated_at : timestamp
}

entity "sale_items" as sale_items {
  * id : bigint <<PK>>
  --
  * sale_id : bigint <<FK>>
  * product_id : bigint <<FK>>
  * quantity : integer
  * price : decimal(10,2)
  * subtotal : decimal(10,2)
  * created_at : timestamp
  * updated_at : timestamp
}

entity "products" as products {
  * id : bigint <<PK>>
  --
  * name : varchar
  * price : decimal(10,2)
}

sales }o--|| customers : customer_id
sales }o--|| users : user_id
sale_items }o--|| sales : sale_id
sale_items }o--|| products : product_id

@enduml
```

### 6. Discount Transaction Module

```plantuml
@startuml DiscountTransactions

entity "sales" as sales {
  * id : bigint <<PK>>
  --
  * customer_id : bigint <<FK>>
  * user_id : bigint <<FK>>
  * total : decimal(10,2)
  * payment_method : enum(cash, gcash, card)
  * created_at : timestamp
  * updated_at : timestamp
}

entity "senior_citizen_transactions" as senior_citizen_transactions {
  * id : bigint <<PK>>
  --
  * sale_id : bigint <<FK>>
  * sc_id_number : varchar
  * sc_name : varchar
  * sc_birthdate : date
  * original_amount : decimal(10,2)
  * discount_amount : decimal(10,2)
  * final_amount : decimal(10,2)
  * discount_percentage : decimal(5,2)
  * items_purchased : text
  * created_at : timestamp
  * updated_at : timestamp
}

entity "pwd_transactions" as pwd_transactions {
  * id : bigint <<PK>>
  --
  * sale_id : bigint <<FK>>
  * pwd_id_number : varchar
  * pwd_name : varchar
  * pwd_birthdate : date
  disability_type : varchar
  * original_amount : decimal(10,2)
  * discount_amount : decimal(10,2)
  * final_amount : decimal(10,2)
  * discount_percentage : decimal(5,2)
  * items_purchased : text
  * created_at : timestamp
  * updated_at : timestamp
}

senior_citizen_transactions }o--|| sales : sale_id
pwd_transactions }o--|| sales : sale_id

@enduml
```

### 7. Audit & Logging Module

```plantuml
@startuml AuditLogs

entity "users" as users {
  * id : bigint <<PK>>
  --
  * name : varchar
  * email : varchar
  * role : enum(admin, cashier)
}

entity "audit_logs" as audit_logs {
  * id : bigint <<PK>>
  --
  * user_id : bigint <<FK>>
  * action : varchar
  * description : text
  auditable_type : varchar
  auditable_id : bigint
  old_values : json
  new_values : json
  ip_address : varchar
  user_agent : text
  * created_at : timestamp
  * updated_at : timestamp
}

audit_logs }o--|| users : user_id

@enduml
```

shelf_movements }o--|| products : product_id
shelf_movements }o--|| users : user_id
audit_logs }o--|| users : user_id

@enduml

```

```
