# iPharma Mart Management System - QA Testing Checklist

**Date:** December 5, 2025
**Version:** 1.0
**Database:** Seeded with comprehensive data (January 2025 - December 2025)

---

## 🎯 Testing Overview

This checklist covers all implemented features for quality assurance and scalability testing.

### Test Data Summary

-   **Users:** 5 (1 Superadmin, 1 Admin, 2 Cashiers, 1 Inventory Manager)
-   **Products:** 50 (Pharmacy + Mini Mart items)
-   **Customers:** 40 (12 Senior Citizens, 8 PWD, 20 Regular)
-   **Sales Transactions:** 280 (spread across 11 months)
-   **Senior Citizen Transactions:** 83
-   **PWD Transactions:** 56
-   **Stock Movements:** 160
-   **Shelf Movements:** 85

### Test Credentials

```
Superadmin: superadmin / password
Admin: admin / password
Cashier 1: cashier1 / password
Cashier 2: cashier2 / password
Inventory Manager: inventory1 / password
```

---

## ✅ Feature Testing Checklist

### 1. Authentication & Security

-   [ ] Login with each user role (superadmin, admin, cashier, inventory_manager)
-   [ ] Verify autocomplete is disabled on all forms (inspect with DevTools)
-   [ ] Test logout functionality
-   [ ] Verify role-based redirects (cashier → POS, others → Dashboard)
-   [ ] Test password field autocomplete prevention
-   [ ] Verify session persistence
-   [ ] Test throttling on login attempts (5 attempts per minute)

### 2. Dashboard Features

#### Role-Based Data Display

-   [ ] Login as **Cashier** - verify only personal sales data shown
-   [ ] Login as **Admin** - verify all sales data visible
-   [ ] Login as **Superadmin** - verify complete system overview

#### Date Range Filtering

-   [ ] Test default filter (month-to-date from Dec 1-5, 2025)
-   [ ] Apply custom date range (e.g., Jan 1 - Mar 31, 2025)
-   [ ] Apply full year range (Jan 1 - Dec 5, 2025)
-   [ ] Test "Reset" button - verify returns to default month-to-date
-   [ ] Verify data updates correctly with each filter change
-   [ ] Check revenue totals match filtered period
-   [ ] Verify transaction counts match filtered period

#### Dashboard Metrics

-   [ ] Verify Total Revenue displays correctly (hidden until hover/press)
-   [ ] Check Total Products count (should be 50)
-   [ ] Check Total Customers count (should be 40)
-   [ ] Check Low Stock Items count
-   [ ] Verify Monthly Sales calculation
-   [ ] Check Top Products section shows correct sales data
-   [ ] Verify Low Stock Products table
-   [ ] Check Expiry Alerts section (products expiring soon)
-   [ ] Verify Expired Batches section

### 3. Point of Sale (POS) - Cashier Only

#### Product Search & Cart

-   [ ] Search product by name (e.g., "Biogesic")
-   [ ] Search product by barcode
-   [ ] Add product to cart via grid
-   [ ] Add product to cart via search
-   [ ] Verify stock validation prevents over-ordering
-   [ ] Test editable quantity input field
-   [ ] Test quantity increase (+) button
-   [ ] Test quantity decrease (-) button
-   [ ] Verify quantity cannot go below 1
-   [ ] Verify quantity cannot exceed available stock
-   [ ] Remove product from cart
-   [ ] Clear entire cart

#### Customer Selection & Discount

-   [ ] Select "New Customer" option
-   [ ] Enter new customer details
-   [ ] Select "Existing Customer" option
-   [ ] Test customer search by name
-   [ ] Test customer search by phone number
-   [ ] Check Senior Citizen discount checkbox
-   [ ] Verify customer dropdown filters to show only SC customers
-   [ ] Select a Senior Citizen customer
-   [ ] Verify SC ID auto-fills
-   [ ] Check PWD discount checkbox
-   [ ] Verify customer dropdown filters to show only PWD customers
-   [ ] Select a PWD customer
-   [ ] Verify PWD ID auto-fills
-   [ ] Uncheck discount - verify all customers shown again
-   [ ] Test discount calculation (20% for SC/PWD)

#### Payment Processing

-   [ ] Complete sale with Cash payment
-   [ ] Complete sale with GCash payment
-   [ ] Complete sale with Card payment
-   [ ] Verify change calculation
-   [ ] Test insufficient payment amount validation
-   [ ] Verify receipt generation
-   [ ] Check stock deduction after sale
-   [ ] Verify transaction saved in Sales History

#### Stock Validation

-   [ ] Attempt to add product with 0 stock - verify error
-   [ ] Attempt to order more than available stock - verify error
-   [ ] Verify error message shows actual available stock

### 4. Customer Management

-   [ ] View customers list
-   [ ] Create new customer with Senior Citizen details
-   [ ] Create new customer with PWD details
-   [ ] Create new regular customer
-   [ ] Edit customer information
-   [ ] Update Senior Citizen ID
-   [ ] Update PWD ID
-   [ ] Search customers by name
-   [ ] Filter customers (if implemented)
-   [ ] Verify pagination works
-   [ ] Test customer validation (required fields)

### 5. Unified Reports (Admin Only)

#### Report Types

-   [ ] Access Reports page (should show Sales by default)
-   [ ] Switch to Sales Report tab
-   [ ] Switch to Inventory Report tab
-   [ ] Switch to Senior Citizen Report tab
-   [ ] Switch to PWD Report tab

#### Date Filtering

-   [ ] Apply date filter to Sales Report
-   [ ] Apply date filter to Inventory Report
-   [ ] Apply date filter to Senior Citizen Report
-   [ ] Apply date filter to PWD Report
-   [ ] Test default date range (month-to-date)
-   [ ] Test custom date range across multiple months

#### Sales Report Content

-   [ ] Verify Total Revenue calculation
-   [ ] Verify Total Transactions count
-   [ ] Verify Average Transaction calculation
-   [ ] Check sales table data accuracy
-   [ ] Verify pagination works
-   [ ] Check receipt numbers are unique
-   [ ] Verify customer names display correctly
-   [ ] Check cashier names display correctly

#### Inventory Report Content

-   [ ] Verify Total Products count
-   [ ] Verify Low Stock count
-   [ ] Verify Out of Stock count
-   [ ] Verify Stock Value calculation
-   [ ] Check product details (name, category, supplier)
-   [ ] Verify shelf stock + back stock = total stock
-   [ ] Check "Sold (Period)" column accuracy
-   [ ] Verify status badges (In Stock, Low Stock, Out of Stock)

#### Senior Citizen Report Content

-   [ ] Verify Total Transactions count (should be 83)
-   [ ] Verify Total Discount Given
-   [ ] Check SC ID numbers display
-   [ ] Verify discount amount calculations (20%)
-   [ ] Check original amount vs discounted amount

#### PWD Report Content

-   [ ] Verify Total Transactions count (should be 56)
-   [ ] Verify Total Discount Given
-   [ ] Check PWD ID numbers display
-   [ ] Verify discount amount calculations (20%)
-   [ ] Check original amount vs discounted amount

### 6. Unified Discounts (Admin & Cashier)

#### Discount Types

-   [ ] Access Discounts page (should show Senior Citizen by default)
-   [ ] Switch to Senior Citizen tab
-   [ ] Switch to PWD tab

#### Date Filtering

-   [ ] Apply date filter to Senior Citizen transactions
-   [ ] Apply date filter to PWD transactions
-   [ ] Test default date range (month-to-date)
-   [ ] Test custom date range

#### Discount Content

-   [ ] Verify transaction counts match
-   [ ] Verify total discount amounts
-   [ ] Check ID numbers display correctly
-   [ ] Verify customer names
-   [ ] Check cashier names
-   [ ] Verify discount calculations (20%)
-   [ ] Test pagination
-   [ ] Verify "No transactions found" message when appropriate

### 7. General Settings (Admin Only)

#### Display Settings

-   [ ] Access Settings page
-   [ ] View current pagination setting (default: 15)
-   [ ] Change pagination to 10
-   [ ] Save settings
-   [ ] Verify pagination updates in other pages
-   [ ] Change pagination to 25
-   [ ] Verify change persists after logout/login

#### Data Management Settings

-   [ ] View current data retention period (default: 365 days)
-   [ ] Change retention period to 180 days
-   [ ] Save settings
-   [ ] Verify setting persists

#### Alert Settings

-   [ ] View current expiry alert days (default: 7)
-   [ ] Change to 14 days
-   [ ] Save settings
-   [ ] Verify expiring products alert updates
-   [ ] Test with different alert periods (1, 7, 30, 90 days)
-   [ ] Enable/disable Low Stock Alerts
-   [ ] Enable/disable Automatic Backups

#### Expiry Alerts

-   [ ] Verify expiry alert section shows on Settings page
-   [ ] Check products expiring within alert period
-   [ ] Verify batch numbers display
-   [ ] Check expiry dates are accurate
-   [ ] Verify "expires in X days" calculation

#### System Actions

-   [ ] Test "Clear Cache" button
-   [ ] Verify success message
-   [ ] Test "Review Old Data" button
-   [ ] Verify confirmation dialog
-   [ ] Check info message about data retention

### 8. Inventory Management (Admin & Inventory Manager)

#### Products

-   [ ] View products list
-   [ ] Create new product
-   [ ] Edit product details
-   [ ] Update shelf stock
-   [ ] Update back stock
-   [ ] Set low stock threshold
-   [ ] Set danger level
-   [ ] Test product search
-   [ ] Verify total_stock calculation (shelf + back)

#### Stock Movements

-   [ ] View stock movements history
-   [ ] Create Stock In movement
-   [ ] Create Stock Out movement
-   [ ] Verify stock updates after movements
-   [ ] Check reference numbers
-   [ ] Test date filtering
-   [ ] Verify pagination

#### Shelf Movements

-   [ ] View shelf movements history
-   [ ] Create shelf movement (back → shelf)
-   [ ] Verify shelf stock increases
-   [ ] Verify back stock decreases
-   [ ] Check movement history accuracy

#### Suppliers (Admin Only)

-   [ ] View suppliers list
-   [ ] Create new supplier
-   [ ] Edit supplier details
-   [ ] Test supplier search
-   [ ] Verify pagination

### 9. Sales History (Admin & Cashier)

-   [ ] View sales history
-   [ ] Filter by date range
-   [ ] Search by receipt number
-   [ ] View sale details
-   [ ] Check sale items list
-   [ ] Verify discount information displays (SC/PWD)
-   [ ] Test pagination
-   [ ] Verify cashier only sees own sales

### 10. UI/UX Features

#### Header

-   [ ] Verify profile dropdown in header (not sidebar)
-   [ ] Click profile avatar - dropdown opens
-   [ ] Check user name displays
-   [ ] Check role displays correctly
-   [ ] Click "Profile Settings" option
-   [ ] Click "Logout" option
-   [ ] Test click-away to close dropdown

#### Sidebar

-   [ ] Verify logo is sticky when scrolling
-   [ ] Scroll sidebar content - logo stays at top
-   [ ] Check navigation items for current role
-   [ ] Verify active page highlighting
-   [ ] Test mobile menu toggle
-   [ ] Check section headers (Sales, Inventory, Reports, etc.)

#### Navigation

-   [ ] Verify Reports appears as single menu item (not 4 separate)
-   [ ] Verify Discounts appears as single menu item (not 2 separate)
-   [ ] Check Settings menu item (Admin only)
-   [ ] Test all navigation links
-   [ ] Verify role-based menu visibility

### 11. User Management (Superadmin Only)

-   [ ] Login as superadmin
-   [ ] View users list
-   [ ] Create new user
-   [ ] Edit user details
-   [ ] Change user role
-   [ ] Test user search
-   [ ] Verify pagination
-   [ ] Check audit logs access

### 12. Audit Logs (Superadmin Only)

-   [ ] Access audit logs
-   [ ] Filter by date range
-   [ ] Filter by user
-   [ ] Filter by action type
-   [ ] Verify all user actions are logged
-   [ ] Test pagination

---

## 🧪 Scalability Testing

### Performance Tests

-   [ ] Load products page with 50 products - check response time
-   [ ] Load sales history with 280 transactions - check response time
-   [ ] Filter reports by full year (Jan-Dec 2025) - check query performance
-   [ ] Test pagination with large dataset
-   [ ] Search products with various keywords - check speed
-   [ ] Search customers with various keywords - check speed

### Data Integrity Tests

-   [ ] Verify all sales have corresponding sale_items
-   [ ] Check all discount transactions link to valid sales
-   [ ] Verify stock movements reference valid products
-   [ ] Check shelf movements reference valid batches
-   [ ] Verify customer discount fields are consistent
-   [ ] Check date consistency across related records

### Edge Cases

-   [ ] Product with 0 stock - verify cannot be sold
-   [ ] Product with exact threshold stock - verify warning
-   [ ] Expired batch - verify cannot be used in POS
-   [ ] Customer with both SC and PWD flags - verify behavior
-   [ ] Sale with 0 items - verify validation prevents
-   [ ] Discount without ID number - verify validation
-   [ ] Date filter with end before start - verify validation
-   [ ] Pagination beyond available pages - verify handling

---

## 📊 Data Validation

### Sales Data

-   [ ] Total sales count: 280 transactions
-   [ ] Date range: January 1, 2025 - December 5, 2025
-   [ ] Sale items: 1,077 total items sold
-   [ ] Senior Citizen transactions: 83 (29.6% of sales)
-   [ ] PWD transactions: 56 (20% of sales)
-   [ ] Verify discount amounts are 20% of original amount
-   [ ] Check all sales have valid payment methods
-   [ ] Verify all sales have valid receipt numbers

### Customer Data

-   [ ] Total customers: 40
-   [ ] Senior Citizens: 12 with valid SC IDs (format: SC-2025-XXXX)
-   [ ] PWD customers: 8 with valid PWD IDs (format: PWD-2025-XXXXXX)
-   [ ] Regular customers: 20
-   [ ] Verify all have phone numbers
-   [ ] Check email format validation

### Product Data

-   [ ] Total products: 50
-   [ ] Pharmacy items: 20
-   [ ] Mini Mart items: 30
-   [ ] All products have categories
-   [ ] All products have suppliers
-   [ ] Verify shelf_stock + back_stock calculation
-   [ ] Check all have valid barcodes

### Stock Data

-   [ ] Product batches: 100 (2 per product)
-   [ ] Stock movements: 160 (120 IN, 40 OUT)
-   [ ] Shelf movements: 85
-   [ ] Verify all movements have reference numbers
-   [ ] Check all movements have valid dates
-   [ ] Verify stock calculations are accurate

---

## 🔍 Browser Compatibility Testing

### Desktop Browsers

-   [ ] Google Chrome (latest)
-   [ ] Mozilla Firefox (latest)
-   [ ] Microsoft Edge (latest)
-   [ ] Safari (if available)

### Mobile Responsiveness

-   [ ] Test on mobile viewport (375px width)
-   [ ] Test on tablet viewport (768px width)
-   [ ] Test on desktop viewport (1920px width)
-   [ ] Check mobile menu functionality
-   [ ] Verify touch interactions work
-   [ ] Check form inputs on mobile

### Specific Browser Features

-   [ ] Autocomplete prevention in Chrome
-   [ ] Autocomplete prevention in Firefox
-   [ ] Autocomplete prevention in Edge
-   [ ] Date picker functionality across browsers
-   [ ] Dropdown menus work consistently
-   [ ] Modal dialogs display correctly

---

## 🐛 Known Issues / Notes

### Issues to Watch

1. Autocomplete prevention may not work in older browsers
2. Date picker format may vary by browser locale
3. Large datasets (>1000 records) may need optimization
4. Cache clearing requires manual page refresh

### Performance Notes

-   Pagination set to 15 by default (configurable via Settings)
-   Reports load data on-demand (not pre-cached)
-   Date filters query database directly
-   Stock calculations are done on-the-fly

### Recommendations

1. Regular database backups before testing
2. Test with fresh seeded data for consistency
3. Clear browser cache between role tests
4. Use incognito mode to test fresh sessions
5. Monitor console for JavaScript errors
6. Check network tab for slow queries

---

## ✅ Sign-Off

### Tested By

-   **Name:** ********\_********
-   **Date:** ********\_********
-   **Role:** ********\_********

### Test Results

-   **Pass Rate:** **\_**%
-   **Critical Issues:** \_\_\_\_
-   **Minor Issues:** \_\_\_\_
-   **Recommendations:** \_\_\_\_

### Notes

---

---

---

---

**End of Testing Checklist**
