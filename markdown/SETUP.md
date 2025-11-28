# iPharma Mart - Pharmacy Management System

A comprehensive pharmacy management system built with Laravel 11, Tailwind CSS 4, and following modern web design principles. This system manages inventory, sales, customers, suppliers, and users with an intuitive interface matching your Figma design.

## Features

### 📊 Dashboard

-   Total revenue tracking
-   Product count monitoring
-   Customer statistics
-   Low stock alerts
-   Recent sales overview
-   Real-time inventory status

### 📦 Inventory Management

-   Product listing with detailed information
-   Category-based organization
-   Stock level monitoring
-   Price management
-   Supplier tracking
-   Expiry date alerts
-   Low stock status indicators

### 💰 Sales Management

-   Create new sales transactions
-   View sales history
-   Customer purchase records
-   Detailed sales receipts
-   Automatic stock updates

### 👥 Customer Management

-   Customer database
-   Contact information
-   Purchase history
-   Easy CRUD operations

### 🏢 Supplier Management

-   Supplier directory
-   Contact details
-   Product associations
-   Supply management

### 👤 User Management

-   User accounts
-   Role management
-   Access control

## Design System

The application implements a comprehensive design system with:

-   **Brand Colors**: Professional green color scheme (#2C6356, #3A7D6F, #4A9680)
-   **Accent Colors**: Orange, Blue, Red, Yellow for visual hierarchy
-   **Typography**: Inter font family with clear hierarchy
-   **Components**: Reusable cards, tables, badges, buttons, forms
-   **Responsive Layout**: Fixed sidebar with main content area
-   **Status Indicators**: Color-coded badges for stock levels

## Technology Stack

-   **Backend**: Laravel 11.x
-   **Frontend**: Blade Templates, Tailwind CSS 4.x
-   **Database**: MySQL/PostgreSQL/SQLite (configurable)
-   **Build Tool**: Vite

## Installation

### Prerequisites

-   PHP 8.2 or higher
-   Composer
-   Node.js & npm
-   Database (MySQL, PostgreSQL, or SQLite)

### Setup Steps

1. **Install PHP dependencies**

    ```powershell
    composer install
    ```

2. **Install Node dependencies**

    ```powershell
    npm install
    ```

3. **Configure environment**

    ```powershell
    # Copy the environment file
    Copy-Item .env.example .env

    # Generate application key
    php artisan key:generate
    ```

4. **Configure database**

    Edit `.env` file with your database credentials:

    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=ipharmamart
    DB_USERNAME=your_username
    DB_PASSWORD=your_password
    ```

5. **Run migrations**

    ```powershell
    php artisan migrate
    ```

6. **Seed the database**

    ```powershell
    php artisan db:seed
    ```

7. **Build assets**

    ```powershell
    npm run build
    ```

8. **Start development server**

    ```powershell
    # Terminal 1: Start Laravel server
    php artisan serve

    # Terminal 2: Start Vite dev server (for hot reload)
    npm run dev
    ```

9. **Access the application**

    Open your browser and navigate to: `http://localhost:8000`

    **Default Login Credentials:**

    - Email: `admin@ipharmamart.com`
    - Password: `password`

## Database Schema

### Tables

-   `users` - System users
-   `categories` - Product categories
-   `suppliers` - Supplier information
-   `customers` - Customer records
-   `products` - Inventory items
-   `sales` - Sales transactions
-   `sale_items` - Individual items in sales

### Relationships

-   Products belong to Categories and Suppliers
-   Sales belong to Customers and Users
-   Sale Items link Products to Sales

## Sample Data

The seeder creates:

-   1 admin user
-   4 product categories (Pain Relief, Antibiotics, Supplements, First Aid)
-   3 suppliers (MedSupply Co, PharmaCorp, HealthPlus)
-   3 customers (John Smith, Sarah Johnson, Mike Wilson)
-   4 products (Paracetamol, Amoxicillin, Vitamin D3, Ibuprofen)
-   3 sales transactions

## Routes

```
GET  /                           → Dashboard (redirects)
GET  /dashboard                  → Dashboard view
GET  /inventory                  → List products
GET  /inventory/create           → Create product form
POST /inventory                  → Store new product
GET  /inventory/{id}/edit        → Edit product form
PUT  /inventory/{id}             → Update product
DELETE /inventory/{id}           → Delete product
GET  /sales                      → List sales
GET  /sales/create               → Create sale form
POST /sales                      → Store new sale
GET  /sales/{id}                 → View sale details
GET  /customers                  → List customers
GET  /customers/create           → Create customer form
POST /customers                  → Store new customer
GET  /customers/{id}/edit        → Edit customer form
PUT  /customers/{id}             → Update customer
DELETE /customers/{id}           → Delete customer
GET  /suppliers                  → List suppliers
GET  /suppliers/create           → Create supplier form
POST /suppliers                  → Store new supplier
GET  /suppliers/{id}/edit        → Edit supplier form
PUT  /suppliers/{id}             → Update supplier
DELETE /suppliers/{id}           → Delete supplier
GET  /users                      → List users
```

## Project Structure

```
app/
├── Http/Controllers/
│   ├── DashboardController.php
│   ├── InventoryController.php
│   ├── SalesController.php
│   ├── CustomerController.php
│   ├── SupplierController.php
│   └── UserController.php
├── Models/
│   ├── Category.php
│   ├── Customer.php
│   ├── Product.php
│   ├── Sale.php
│   ├── SaleItem.php
│   └── Supplier.php
resources/
├── css/
│   └── app.css              # Design system & Tailwind
├── views/
│   ├── components/
│   │   ├── layout.blade.php # Main layout
│   │   ├── sidebar.blade.php
│   │   ├── header.blade.php
│   │   └── stat-card.blade.php
│   ├── dashboard.blade.php
│   ├── inventory/
│   ├── sales/
│   ├── customers/
│   ├── suppliers/
│   └── users/
database/
├── migrations/             # Database schema
└── seeders/               # Sample data
```

## Customization

### Colors

Edit `resources/css/app.css` to customize the color scheme:

```css
--color-brand-green-dark: #2c6356;
--color-brand-green: #3a7d6f;
--color-accent-orange: #ff9052;
```

### Logo

Update the logo in `resources/views/components/sidebar.blade.php`

### Design Tokens

All design variables are in `resources/css/app.css` under `:root`

## Development

```powershell
# Run development server with hot reload
npm run dev

# Build for production
npm run build

# Run migrations
php artisan migrate

# Refresh database with seed data
php artisan migrate:fresh --seed

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

## Production Deployment

1. Set `APP_ENV=production` in `.env`
2. Set `APP_DEBUG=false` in `.env`
3. Run `composer install --optimize-autoloader --no-dev`
4. Run `npm run build`
5. Run `php artisan config:cache`
6. Run `php artisan route:cache`
7. Run `php artisan view:cache`
8. Set proper file permissions
9. Configure web server (Apache/Nginx)

## Security

-   Change default credentials immediately
-   Keep Laravel and dependencies updated
-   Use strong database passwords
-   Enable HTTPS in production
-   Configure CORS properly
-   Implement proper authentication/authorization

## Support

For issues or questions, please refer to:

-   Laravel Documentation: https://laravel.com/docs
-   Tailwind CSS Documentation: https://tailwindcss.com/docs

## License

This project is proprietary software for iPharma Mart.

---

**Built with ❤️ using Laravel & Tailwind CSS**
