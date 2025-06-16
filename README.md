# 🧾 Laravel Mini ERP Assessment

This is a Laravel 12-based Mini ERP application developed as part of a Senior PHP Laravel Developer job assessment.

It demonstrates complete ERP features including role-based access, product inventory management, sales order creation with stock checks, and dashboard reporting.

## ⚙️ Tech Stack

-   Laravel 12.18
-   PHP 8.3+
-   MySQL
-   Bootstrap 5
-   jQuery + DataTables
-   dompdf (barryvdh/laravel-dompdf)

---

## 🚀 Setup Instructions

### Requirements

-   PHP 8.3+
-   Composer
-   MySQL or compatible DB

### Installation

```bash
git clone https://github.com/Pratikakamble/erp_project.git
cd erp_project

composer install
cp .env.example .env
php artisan key:generate

# Configure your DB in .env
php artisan migrate --seed

php artisan serve
```

---

## ✅ Features Implemented

### 🔐 Access Control

-   Laravel Breeze authentication
-   Role-based access using middleware and gates

### 🧭 Dashboard

-   Total sales amount
-   Total number of sales orders
-   Low stock product alerts

### 📦 Inventory (Product) Management

-   Product CRUD using Bootstrap 5 + AJAX
-   Server-side validation using `FormRequest`
-   Dynamic DataTables integration

### 🧾 Sales Order Module

-   Multi-product sales order form
-   Quantity and subtotal calculations in real time
-   Form validation with inline error display via AJAX
-   Stock validation before order placement
-   Database transaction ensures rollback on failure
-   PDF Invoice export using `barryvdh/laravel-dompdf`

---
