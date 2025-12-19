# 🔍 Quality Assurance Testing Checklist - Final Version

## iPharma Mart Management System

**Date:** December 19, 2025  
**Version:** 1.0 (Post Final Defense Revision)  
**Tester:** **********\_\_\_**********

---

## ✅ Pre-Testing Setup

-   [ ] Database is properly seeded with test data
-   [ ] All dependencies installed (`composer install`, `npm install`)
-   [ ] Environment file configured correctly
-   [ ] Application key generated
-   [ ] Storage permissions set properly
-   [ ] Cache cleared (`php artisan cache:clear`)

---

## 🔐 1. Authentication & User Management

### Login System

-   [ ] **Test 1.1:** Login with valid credentials (Admin)
-   [ ] **Test 1.2:** Login with valid credentials (Cashier)
-   [ ] **Test 1.3:** Login with valid credentials (Inventory Manager)
-   [ ] **Test 1.4:** Login with valid credentials (Super Admin)
-   [ ] **Test 1.5:** Login fails with invalid credentials
-   [ ] **Test 1.6:** Appropriate error messages displayed
-   [ ] **Test 1.7:** Logout functionality works correctly
-   [ ] **Test 1.8:** Session management persists across page refreshes

### User Creation & Role Management

-   [ ] **Test 1.9:** Super Admin can only create Admin accounts
-   [ ] **Test 1.10:** Admin can create Cashier accounts
-   [ ] **Test 1.11:** Admin can create Inventory Manager accounts
-   [ ] **Test 1.12:** Role restrictions enforced in dropdown
-   [ ] **Test 1.13:** User creation validation works (required fields)
-   [ ] **Test 1.14:** Password confirmation matches
-   [ ] **Test 1.15:** Edit user functionality works
-   [ ] **Test 1.16:** User list displays correctly with proper roles

---

## 📊 2. Dashboard

### Metrics Display

-   [ ] **Test 2.1:** Total Sales displays correctly (right-aligned)
-   [ ] **Test 2.2:** Total Transactions count is accurate
-   [ ] **Test 2.3:** Expiring Products count is correct
-   [ ] **Test 2.4:** Expired Products count displays properly
-   [ ] **Test 2.5:** Low Stock Items count is accurate
-   [ ] **Test 2.6:** "Total Sales" label (not "Total Revenue")
-   [ ] **Test 2.7:** Revenue hover-to-show feature works
-   [ ] **Test 2.8:** Date filter functionality works correctly
-   [ ] **Test 2.9:** Reset filter returns to default date range

### Visual Elements

-   [ ] **Test 2.10:** All cards have proper border colors
-   [ ] **Test 2.11:** Numbers are right-aligned in metric cards
-   [ ] **Test 2.12:** Top products display correctly
-   [ ] **Test 2.13:** Low stock products display with details
-   [ ] **Test 2.14:** Expiry alerts show batch information
-   [ ] **Test 2.15:** Product associations display frequency
-   [ ] **Test 2.16:** ML insights show trends accurately
-   [ ] **Test 2.17:** Cashiers see only their own data

---

## 🛒 3. Point of Sale (POS)

### Core Functionality

-   [ ] **Test 3.1:** POS loads in fullscreen mode
-   [ ] **Test 3.2:** Fullscreen toggle button works
-   [ ] **Test 3.3:** Product search works correctly
-   [ ] **Test 3.4:** Products display with batch information
-   [ ] **Test 3.5:** Products display earliest expiry date
-   [ ] **Test 3.6:** Add to cart functionality works
-   [ ] **Test 3.7:** Cart quantity updates correctly
-   [ ] **Test 3.8:** Remove from cart works
-   [ ] **Test 3.9:** Stock validation prevents overselling
-   [ ] **Test 3.10:** Category filter works

### Stock Management (FIFO)

-   [ ] **Test 3.11:** Products with batches use FIFO deduction
-   [ ] **Test 3.12:** Earliest expiry batch is deducted first
-   [ ] **Test 3.13:** Stock moves from shelf first, then back
-   [ ] **Test 3.14:** Non-expiring products use simple stock method
-   [ ] **Test 3.15:** Batch quantities update correctly after sale
-   [ ] **Test 3.16:** Product total stock updates after sale

### Customer Management

-   [ ] **Test 3.17:** Walk-in customer selection works
-   [ ] **Test 3.18:** Existing customer selection works
-   [ ] **Test 3.19:** New customer creation works
-   [ ] **Test 3.20:** Customer validation enforces required fields

### Discount System

-   [ ] **Test 3.21:** Senior Citizen discount checkbox works
-   [ ] **Test 3.22:** Senior ID number field enables when checked
-   [ ] **Test 3.23:** Senior name field enables when checked
-   [ ] **Test 3.24:** Senior name is required before checkout
-   [ ] **Test 3.25:** PWD discount checkbox works
-   [ ] **Test 3.26:** PWD ID number field enables when checked
-   [ ] **Test 3.27:** PWD name field enables when checked
-   [ ] **Test 3.28:** PWD name is required before checkout
-   [ ] **Test 3.29:** Only one discount type can be selected
-   [ ] **Test 3.30:** 20% discount calculates correctly
-   [ ] **Test 3.31:** Discount recipient name saves to database

### Payment Processing

-   [ ] **Test 3.32:** Cash payment method works
-   [ ] **Test 3.33:** GCash payment requires reference number
-   [ ] **Test 3.34:** Card payment requires all card details
-   [ ] **Test 3.35:** Payment amount validation works
-   [ ] **Test 3.36:** Change calculation is accurate
-   [ ] **Test 3.37:** Payment insufficient validation works
-   [ ] **Test 3.38:** Receipt displays correctly
-   [ ] **Test 3.39:** Receipt shows all transaction details
-   [ ] **Test 3.40:** Receipt shows discount information
-   [ ] **Test 3.41:** Print receipt functionality works

### Transaction Management

-   [ ] **Test 3.42:** Void item requires admin authorization
-   [ ] **Test 3.43:** Void entire sale requires admin authorization
-   [ ] **Test 3.44:** Admin credentials verification works
-   [ ] **Test 3.45:** Void reason is required and logged
-   [ ] **Test 3.46:** Auto-refresh after transaction completion
-   [ ] **Test 3.47:** Cart clears after successful sale
-   [ ] **Test 3.48:** Fields reset for new transaction
-   [ ] **Test 3.49:** Product list refreshes with updated stock

---

## 📦 4. Inventory Management

### Product Display

-   [ ] **Test 4.1:** Products display with separate batch rows
-   [ ] **Test 4.2:** Batch number column displays correctly
-   [ ] **Test 4.3:** Expiration date column displays correctly
-   [ ] **Test 4.4:** Expired batches marked as "EXPIRED"
-   [ ] **Test 4.5:** Expiring soon batches show days left
-   [ ] **Test 4.6:** Color coding for expiry dates works
-   [ ] **Test 4.7:** Products without batches display properly
-   [ ] **Test 4.8:** Batch quantities (shelf/back) per batch
-   [ ] **Test 4.9:** Total quantities calculated correctly
-   [ ] **Test 4.10:** Multiple batches display in order (FIFO)

### Product Management

-   [ ] **Test 4.11:** Add new product form works
-   [ ] **Test 4.12:** Product validation enforces rules
-   [ ] **Test 4.13:** Edit product functionality works
-   [ ] **Test 4.14:** Pharmacy products show generic name
-   [ ] **Test 4.15:** Mini mart products display correctly
-   [ ] **Test 4.16:** Product type tabs work correctly
-   [ ] **Test 4.17:** Category filter works
-   [ ] **Test 4.18:** Stock status filter works
-   [ ] **Test 4.19:** Search functionality works
-   [ ] **Test 4.20:** Void product requires admin authorization

### Stock Operations

-   [ ] **Test 4.21:** Stock-in creates new batch correctly
-   [ ] **Test 4.22:** Stock-in updates shelf/back quantities
-   [ ] **Test 4.23:** Stock-out reduces correct quantities
-   [ ] **Test 4.24:** Restock updates quantities correctly
-   [ ] **Test 4.25:** Move to shelf/back operations work
-   [ ] **Test 4.26:** Stock movements logged correctly
-   [ ] **Test 4.27:** Stock validation prevents negative values

---

## 💰 5. Sales History

### Display & Filtering

-   [ ] **Test 5.1:** Sales transactions display correctly
-   [ ] **Test 5.2:** Source column shows "POS" badge
-   [ ] **Test 5.3:** Customer names display properly
-   [ ] **Test 5.4:** Payment method badges display with colors
-   [ ] **Test 5.5:** Payment method badges have backgrounds
-   [ ] **Test 5.6:** Date/time format is correct
-   [ ] **Test 5.7:** Customer filter works
-   [ ] **Test 5.8:** Payment method filter works
-   [ ] **Test 5.9:** Date range filter works
-   [ ] **Test 5.10:** Pagination works correctly
-   [ ] **Test 5.11:** Cashiers see only their sales

### Transaction Details

-   [ ] **Test 5.12:** Sale details page displays correctly
-   [ ] **Test 5.13:** Items list with quantities displayed
-   [ ] **Test 5.14:** Payment information shown
-   [ ] **Test 5.15:** Discount information displayed (if applied)
-   [ ] **Test 5.16:** Customer information shown
-   [ ] **Test 5.17:** Voided sales marked clearly

---

## 📈 6. Reports

### Metrics Layout

-   [ ] **Test 6.1:** Sales report metrics right-aligned
-   [ ] **Test 6.2:** Inventory report metrics right-aligned
-   [ ] **Test 6.3:** Senior Citizen report metrics right-aligned
-   [ ] **Test 6.4:** PWD report metrics right-aligned
-   [ ] **Test 6.5:** All metrics cards match dashboard style
-   [ ] **Test 6.6:** Numbers positioned on right side
-   [ ] **Test 6.7:** Consistent card spacing and layout

### Report Generation

-   [ ] **Test 6.8:** Sales report generates correctly
-   [ ] **Test 6.9:** Inventory report generates correctly
-   [ ] **Test 6.10:** Senior Citizen report generates correctly
-   [ ] **Test 6.11:** PWD report generates correctly
-   [ ] **Test 6.12:** Date filters apply to reports
-   [ ] **Test 6.13:** PDF download works for all reports
-   [ ] **Test 6.14:** PDF formatting is professional
-   [ ] **Test 6.15:** Report data accuracy verified

---

## 💳 7. Discount Transactions

### Senior Citizen

-   [ ] **Test 7.1:** Senior Citizen page displays transactions
-   [ ] **Test 7.2:** Senior names display correctly
-   [ ] **Test 7.3:** ID numbers display correctly
-   [ ] **Test 7.4:** Discount amounts calculated correctly
-   [ ] **Test 7.5:** Monthly summary displays accurately
-   [ ] **Test 7.6:** Filter by name works
-   [ ] **Test 7.7:** Filter by date range works
-   [ ] **Test 7.8:** Sale link navigates correctly

### PWD

-   [ ] **Test 7.9:** PWD page displays transactions
-   [ ] **Test 7.10:** PWD names display correctly
-   [ ] **Test 7.11:** PWD ID numbers display correctly
-   [ ] **Test 7.12:** Disability type shows properly
-   [ ] **Test 7.13:** Discount amounts calculated correctly
-   [ ] **Test 7.14:** Monthly summary displays accurately
-   [ ] **Test 7.15:** Filter by name works
-   [ ] **Test 7.16:** Filter by date range works
-   [ ] **Test 7.17:** Sale link navigates correctly

---

## ⚙️ 8. Settings

### Configuration

-   [ ] **Test 8.1:** Settings page loads correctly
-   [ ] **Test 8.2:** Pagination per page setting works
-   [ ] **Test 8.3:** Data retention in years (not days)
-   [ ] **Test 8.4:** Data retention range: 3-10 years
-   [ ] **Test 8.5:** Expiry alert days setting works
-   [ ] **Test 8.6:** Low stock alert toggle works
-   [ ] **Test 8.7:** Settings save successfully
-   [ ] **Test 8.8:** Validation enforces min/max values

### Database Backup

-   [ ] **Test 8.9:** Auto backup checkbox moved to backup section
-   [ ] **Test 8.10:** Auto backup checkbox saves correctly
-   [ ] **Test 8.11:** Manual backup button works
-   [ ] **Test 8.12:** Backup creates file successfully
-   [ ] **Test 8.13:** Backup list displays correctly
-   [ ] **Test 8.14:** Download backup file works
-   [ ] **Test 8.15:** Delete backup file works
-   [ ] **Test 8.16:** Backup section layout is clean

### System Actions

-   [ ] **Test 8.17:** Clear cache button works
-   [ ] **Test 8.18:** Archive old data works
-   [ ] **Test 8.19:** Confirmation dialogs display
-   [ ] **Test 8.20:** Success messages display correctly

### Expiry Alerts

-   [ ] **Test 8.21:** Expiring batches alert displays
-   [ ] **Test 8.22:** Alert shows within configured days
-   [ ] **Test 8.23:** Batch information complete
-   [ ] **Test 8.24:** Expiry dates formatted correctly

---

## 🎨 9. UI/UX Elements

### Status Badges

-   [ ] **Test 9.1:** Success badges have green background
-   [ ] **Test 9.2:** Danger badges have red background
-   [ ] **Test 9.3:** Warning badges have yellow background
-   [ ] **Test 9.4:** Info badges have blue background
-   [ ] **Test 9.5:** Secondary badges have gray background
-   [ ] **Test 9.6:** All badges have borders
-   [ ] **Test 9.7:** Badge colors not harsh/contrasting
-   [ ] **Test 9.8:** Badge text readable

### General UI

-   [ ] **Test 9.9:** Navigation menu works properly
-   [ ] **Test 9.10:** Sidebar active states work
-   [ ] **Test 9.11:** Responsive design works on mobile
-   [ ] **Test 9.12:** Forms have proper validation styling
-   [ ] **Test 9.13:** Buttons have hover states
-   [ ] **Test 9.14:** Modals open and close properly
-   [ ] **Test 9.15:** Toast notifications display correctly
-   [ ] **Test 9.16:** Loading states work properly
-   [ ] **Test 9.17:** Error messages are user-friendly

---

## 📝 10. Audit Logging

### Activity Tracking

-   [ ] **Test 10.1:** Sale transactions logged
-   [ ] **Test 10.2:** Stock movements logged
-   [ ] **Test 10.3:** User actions logged
-   [ ] **Test 10.4:** Void actions logged with reason
-   [ ] **Test 10.5:** Admin authorizations logged
-   [ ] **Test 10.6:** Product changes logged
-   [ ] **Test 10.7:** Audit log displays correctly
-   [ ] **Test 10.8:** Audit log filterable
-   [ ] **Test 10.9:** Audit log includes user information
-   [ ] **Test 10.10:** Timestamps are accurate

---

## 🔒 11. Security & Permissions

### Role-Based Access

-   [ ] **Test 11.1:** Super Admin has full access
-   [ ] **Test 11.2:** Admin has appropriate access
-   [ ] **Test 11.3:** Cashier limited to POS and sales
-   [ ] **Test 11.4:** Inventory Manager has stock access
-   [ ] **Test 11.5:** Unauthorized pages redirect
-   [ ] **Test 11.6:** API endpoints protected
-   [ ] **Test 11.7:** Direct URL access blocked properly

### Data Protection

-   [ ] **Test 11.8:** Passwords hashed in database
-   [ ] **Test 11.9:** CSRF tokens working
-   [ ] **Test 11.10:** SQL injection prevented
-   [ ] **Test 11.11:** XSS attacks prevented
-   [ ] **Test 11.12:** Session timeout works
-   [ ] **Test 11.13:** Sensitive data hidden from logs

---

## ⚡ 12. Performance & Optimization

### Speed & Efficiency

-   [ ] **Test 12.1:** Dashboard loads in < 2 seconds
-   [ ] **Test 12.2:** POS loads in < 2 seconds
-   [ ] **Test 12.3:** Reports generate in < 5 seconds
-   [ ] **Test 12.4:** Search responses instant (< 500ms)
-   [ ] **Test 12.5:** Pagination works smoothly
-   [ ] **Test 12.6:** Large datasets handled properly
-   [ ] **Test 12.7:** No memory leaks observed
-   [ ] **Test 12.8:** Database queries optimized

---

## 🌐 13. Browser Compatibility

-   [ ] **Test 13.1:** Chrome (latest) - All features work
-   [ ] **Test 13.2:** Firefox (latest) - All features work
-   [ ] **Test 13.3:** Edge (latest) - All features work
-   [ ] **Test 13.4:** Safari (if applicable) - All features work
-   [ ] **Test 13.5:** Mobile browsers - Responsive design works

---

## 📱 14. Responsive Design

-   [ ] **Test 14.1:** Desktop (1920x1080) - Layout correct
-   [ ] **Test 14.2:** Laptop (1366x768) - Layout correct
-   [ ] **Test 14.3:** Tablet (768px) - Layout adapts
-   [ ] **Test 14.4:** Mobile (375px) - Layout adapts
-   [ ] **Test 14.5:** Navigation responsive
-   [ ] **Test 14.6:** Tables scroll on small screens
-   [ ] **Test 14.7:** Forms usable on mobile
-   [ ] **Test 14.8:** POS usable on tablets

---

## 🚨 15. Error Handling

### Validation Errors

-   [ ] **Test 15.1:** Form validation messages clear
-   [ ] **Test 15.2:** Required fields highlighted
-   [ ] **Test 15.3:** Format validation works (email, phone)
-   [ ] **Test 15.4:** Number range validation works
-   [ ] **Test 15.5:** Date validation works

### System Errors

-   [ ] **Test 15.6:** 404 page displays for missing routes
-   [ ] **Test 15.7:** 403 page displays for unauthorized access
-   [ ] **Test 15.8:** 500 errors handled gracefully
-   [ ] **Test 15.9:** Database errors handled properly
-   [ ] **Test 15.10:** Network errors handled properly

---

## 📊 16. Data Integrity

### Database Consistency

-   [ ] **Test 16.1:** No orphaned records in database
-   [ ] **Test 16.2:** Foreign key constraints working
-   [ ] **Test 16.3:** Cascading deletes work correctly
-   [ ] **Test 16.4:** Transactions atomic (all or nothing)
-   [ ] **Test 16.5:** Stock quantities always accurate
-   [ ] **Test 16.6:** Sale totals calculate correctly
-   [ ] **Test 16.7:** Discount amounts accurate
-   [ ] **Test 16.8:** Timestamps accurate

### Data Validation

-   [ ] **Test 16.9:** No negative stock values
-   [ ] **Test 16.10:** No negative prices
-   [ ] **Test 16.11:** Dates in valid range
-   [ ] **Test 16.12:** Quantities within limits
-   [ ] **Test 16.13:** Referential integrity maintained

---

## 🎯 17. Critical Business Logic

### Sales Processing

-   [ ] **Test 17.1:** Sales total = sum of item subtotals
-   [ ] **Test 17.2:** VAT calculation correct (12%)
-   [ ] **Test 17.3:** Discounts apply correctly
-   [ ] **Test 17.4:** Payment change calculated correctly
-   [ ] **Test 17.5:** Receipt numbers sequential and unique

### Stock Management

-   [ ] **Test 17.6:** FIFO enforced for expiring products
-   [ ] **Test 17.7:** Stock never goes negative
-   [ ] **Test 17.8:** Batch expiry tracking accurate
-   [ ] **Test 17.9:** Low stock alerts trigger correctly
-   [ ] **Test 17.10:** Stock movements traceable

---

## ✨ 18. Final Defense Revisions

### Batch System (FIFO)

-   [ ] **Test 18.1:** POS uses FIFO for batch deduction
-   [ ] **Test 18.2:** Batches separated in inventory view
-   [ ] **Test 18.3:** Expiration dates visible per batch
-   [ ] **Test 18.4:** Old batches deducted before new ones
-   [ ] **Test 18.5:** No mixing of old and new batches

### UI Improvements

-   [ ] **Test 18.6:** Report metrics match dashboard style
-   [ ] **Test 18.7:** Numbers right-aligned in all cards
-   [ ] **Test 18.8:** Status badges have backgrounds
-   [ ] **Test 18.9:** No harsh contrasting colors
-   [ ] **Test 18.10:** Consistent spacing and alignment

### Settings Reorganization

-   [ ] **Test 18.11:** Auto backup in Database Backup section
-   [ ] **Test 18.12:** Settings logically grouped
-   [ ] **Test 18.13:** Save button works for auto backup
-   [ ] **Test 18.14:** All settings persist correctly

### Transaction Enhancements

-   [ ] **Test 18.15:** PWD name captured and displayed
-   [ ] **Test 18.16:** Senior name captured and displayed
-   [ ] **Test 18.17:** Transaction source visible
-   [ ] **Test 18.18:** Auto-refresh after transaction

---

## 📋 Testing Summary

**Total Tests:** 300+  
**Passed:** **\_** / **\_**  
**Failed:** **\_**  
**Blocked:** **\_**  
**Pass Rate:** **\_**%

### Critical Issues Found:

1. ***
2. ***
3. ***

### Non-Critical Issues Found:

1. ***
2. ***
3. ***

### Recommendations:

---

---

---

---

## ✅ Sign-Off

**Tester Name:** **********\_\_\_**********  
**Date:** **********\_\_\_**********  
**Signature:** **********\_\_\_**********

**Project Manager:** **********\_\_\_**********  
**Date:** **********\_\_\_**********  
**Signature:** **********\_\_\_**********

---

## 📝 Notes

-   This checklist should be completed for each major release
-   All critical issues must be resolved before production deployment
-   Document any deviations or exceptions
-   Keep screenshots of any issues found
-   Retest all failed items after fixes applied

**Status Legend:**

-   ✅ Pass
-   ❌ Fail
-   ⚠️ Needs Review
-   🚫 Blocked
-   ⏭️ Skipped
