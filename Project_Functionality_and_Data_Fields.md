# Project Functionality and Data Fields Documentation

This document provides a comprehensive list of all functionalities, fields, and data points found in the **Ecom - Marketplace Dashboard Template** project. It has been verified against all 28 HTML template files to ensure accuracy.

---

## 1. Dashboard (Central Overview)
**Functionality:** Real-time business analytics hub and activity monitoring.

*   **Key Metrics (Data Points):**
    *   **Revenue:** Total monetary earnings ($).
    *   **Orders:** Count of total processed orders.
    *   **Products:** Inventory count (segmented by categories).
    *   **Monthly Earning:** Recent financial performance trends.
*   **Visual Analytics:**
    *   **Sale Statistics:** Dynamic charts for periodic sales data.
    *   **Revenue Base on Area:** Geographic sales distribution chart.
    *   **Marketing Channels:** Engagement metrics (Facebook, Instagram, Google, Twitter) via progress bars.
*   **Activity Lists:**
    *   **New Members:** Name, Location, Profile Avatar.
    *   **Recent Activities:** Chronological timeline of system events.
    *   **Latest Orders:** ID, Billing Name, Date, Total, Payment Status (Paid, Chargeback, Refund), Payment Method.

---

## 2. Product Management
**Functionality:** Tools for managing a multi-vendor inventory system.

### A. Product Listing (Grid & List)
*   **Fields:** Product Name, SKU, Category, Price, Status (Active, Draft, Out of Stock), Date Added.
*   **Actions:** Bulk Delete, Search by Name, Filter by Category/Status/Date.

### B. Add/Edit Product (Comprehensive Field List)
*   **General Information:** Product Title, Full Description, SKU, Brand Name (Single/Multi-select), Color, Size.
*   **Pricing:** Regular Price, Promotional Price, Cost in USD, Currency (USD, EUR, RUBL, UZ Som), Tax Rate (%), Tax ID.
*   **Status & Visibility:** Status (Published, Draft), Enable/Disable Toggle, Make Template (Checkbox).
*   **Shipping & Logistics:** Width, Height, Weight (Grams), Shipping Fees ($).
*   **Organization & Media:** Product Images (Multi-file upload), Main Category, Sub-category, Tags (Searchable chips).

---

## 3. Category & Brand Management
**Functionality:** Structural organization and vendor branding.

### A. Categories
*   **Form Fields:** Name, Slug (URL-friendly), Parent Category, Description.
*   **Table Data:** ID, Category Name, Description, Slug, Order/Priority.

### B. Brands
*   **Card Data:** Brand Logo, Brand Name, Total Items Count.
*   **Filters:** Search by Name, Category, Date.

---

## 4. Order & Fulfillment
**Functionality:** End-to-end order processing and documentation.

*   **Order List Fields:** Order ID (#SKU), Customer Name, Email, Total Amount, Status (Pending, Received, Canceled, Delivered), Date.
*   **Order Details:** 
    *   Shipping/Billing Info (Address, City, PO Box).
    *   Payment Method Details.
    *   Itemized List (Product name/description, Price, Qty, Total).
    *   Order Summary (Subtotal, Shipping, Tax, Grand Total).
*   **Order Tracking:** Status timeline (Order Placed, Processing, Shipped, Delivered).
*   **Invoice:** Professional billing document with logo, addresses, and itemization.

---

## 5. Seller Management
**Functionality:** Oversight of marketplace vendors and partners.

*   **Seller List Fields:** Seller Name/ID, Avatar, Email, Status (Active, Inactive, Suspended), Registration Date.
*   **Seller Profile:** Personal/Business Details, Sales Revenue Stats, Product counts, Recent order history.

---

## 6. Transactions & Finance
**Functionality:** Audit trail for all financial movements.

*   **Transaction List:** Transaction ID, Paid Amount, Method (Visa, Mastercard, Paypal, Amex), Status (Success, Pending, Failed), Date/Time.
*   **Detailed Transaction View:** 
    *   Transaction Hash (Cryptographic/Unique ID).
    *   Customer/Business Info (Name, Phone, Email).
    *   Item List & Financial Breakdown.
    *   Transaction Note (Internal/Customer memo).

---

## 7. Review Management
**Functionality:** Customer satisfaction monitoring.

*   **Fields:** Review ID, Associated Product, Customer Name, Rating (1-5 Star Visuals), Date Added, Review Text.
*   **Filters:** Search by Product/Name, Filter by Rating/Status.

---

## 8. Site & Profile Settings
**Functionality:** System-wide configuration and administrator profile management.

### A. Profile Settings (Personal)
*   **Fields:** First/Last Name, Email, Phone, Address, Birthday, Profile Photo.
*   **Security:** Password Change, Account Deactivation.

### B. Site Settings (Administrative)
*   **Website Identity:** Website Name, Home Page Title, Meta Description.
*   **Access Control:** Registration toggles (Enable all, Only buyers, Stop new shops).
*   **Notifications:** Automatic registration alerts, Custom notification text.
*   **Localization:** Main Currency selection, Supported Currencies list.

---

## 9. User Authentication & UI
*   **Auth Pages:** 
    *   **Login:** Username/Email, Password, Remember Me.
    *   **Register:** Full Name, Email, Password, Terms Acceptance.
*   **UI Features:** 
    *   **Global Search:** Search bar with auto-suggestions.
    *   **Notifications Bar:** Real-time alerts for system events.
    *   **Language Selector:** US English, French, Japanese, Chinese.
    *   **Theme Toggle:** Dark Mode and Light Mode support.
    *   **Responsive Sidebar:** Collapsible navigation menu.
