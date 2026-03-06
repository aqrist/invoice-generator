Laravel 12 starter kit Livewire
Laravel's built-in authentication   
single-file Livewire components
---

# PRD — Invoice Generator Web App

## 1. Product Overview

**Nama Produk:** Invoice Generator
**Platform:** Web Application
**Tujuan:**
Menyediakan aplikasi berbasis web yang memungkinkan pengguna untuk:

* Login ke sistem
* Menyimpan profil bisnis
* Membuat invoice secara cepat
* Mengatur komponen invoice secara fleksibel
* Mengunduh invoice dalam format **PDF**
* Mengelola riwayat invoice

Target pengguna:

* Freelancer
* Agency
* Developer
* Konsultan
* UMKM

---

# 2. Core Features

## 2.1 Authentication

User harus login sebelum menggunakan aplikasi.

### Fitur

* Register
* Login
* Logout
* Reset password
* Email verification (opsional)

### Field user

```
name
email
password
created_at
```

---

# 3. Company Profile / Sender Profile

User dapat menyimpan **profil perusahaan** yang otomatis muncul di invoice.

### Field

```
company_name
logo
address
phone
email
website
tax_number (NPWP)
signature (optional)
```

### Feature

* Upload logo
* Upload signature
* Edit profil

---

# 4. Customer Management

User dapat menyimpan daftar customer.

### Field

```
customer_name
company_name
address
email
phone
contact_person
notes
```

### Feature

* Create customer
* Edit customer
* Delete customer
* Select customer saat membuat invoice

---

# 5. Invoice Management

### Create Invoice

User dapat membuat invoice baru dengan komponen berikut.

### Invoice Header

```
invoice_number (auto generate)
invoice_date
due_date
reference_number (optional)
currency
```

### Customer

```
customer_id
atau custom input manual
```

---

# 6. Invoice Items

Invoice dapat memiliki banyak item.

### Struktur Item

```
item_name
description
quantity
unit_price
subtotal
```

### Feature

* Add item
* Remove item
* Edit item
* Auto calculate subtotal

---

# 7. Payment Summary

Sistem otomatis menghitung total pembayaran.

### Field

```
subtotal
discount
tax_percentage
tax_amount
additional_fee
grand_total
```

### Formula

```
subtotal = sum(item subtotal)

tax_amount = subtotal * tax_percentage

grand_total =
subtotal
- discount
+ tax_amount
+ additional_fee
```

---

# 8. Payment Method

User dapat menyimpan metode pembayaran.

### Field

```
bank_name
account_number
account_holder
qris_image (optional)
payment_notes
```

### Feature

* Multiple payment method
* Select payment method di invoice

---

# 9. Notes & Terms

User dapat menambahkan catatan tambahan pada invoice.

### Field

```
notes
terms_conditions
```

Contoh:

* Payment within 7 days
* Non-refundable

---

# 10. Invoice Number Generator

Invoice number otomatis dibuat oleh sistem.

### Format

Contoh:

```
INV-2026-0001
INV-2026-0002
INV-2026-0003
```

### Struktur

```
PREFIX-YEAR-SEQUENCE
```

Contoh alternatif:

```
INV/2026/0001
```

### Rules

* Sequence reset tiap tahun
* Tidak boleh duplikat

---

# 11. Invoice Preview

Sebelum disimpan user dapat melihat preview invoice.

### Layout

Struktur:

```
--------------------------------
LOGO             INVOICE

Company Info     Invoice Info

Bill To
Customer Info

--------------------------------
Items Table
--------------------------------

Subtotal
Tax
Discount
Total

Payment Method

Notes
Signature
--------------------------------
```

---

# 12. Export / Download

User dapat mengunduh invoice.

### Format

* PDF
* Print friendly

---

# 13. Invoice History

User dapat melihat semua invoice yang pernah dibuat.

### List Page

Kolom:

```
invoice_number
customer
date
due_date
total
status
action
```

### Status

```
draft
sent
paid
overdue
```

---

# 14. Dashboard

Halaman utama setelah login.

### Komponen

* Total invoice
* Total revenue
* Invoice unpaid
* Invoice overdue
* Recent invoice list

---

# 15. UI / UX

### Style

* Clean
* Professional
* Minimal

### Layout

Sidebar menu:

```
Dashboard
Invoices
Customers
Company Profile
Payment Methods
Settings
Logout
```

---

# 16. Tech Stack (Recommended)

Karena Yang Mulia ahli Laravel, babu sarankan:

### Backend

* **Laravel 11**

### Admin Panel

* **Filament 3**

### Frontend

* Blade / Livewire / Filament

### PDF Generator

* **dompdf**
  atau
* **snappy wkhtmltopdf**

### Storage

* Logo
* Signature
* QRIS

---

# 17. Database Structure (Simplified)

### users

```
id
name
email
password
```

---

### companies

```
id
user_id
company_name
logo
address
phone
email
website
tax_number
signature
```

---

### customers

```
id
user_id
name
company
address
email
phone
contact_person
```

---

### invoices

```
id
user_id
customer_id
invoice_number
invoice_date
due_date
reference
subtotal
discount
tax
additional_fee
grand_total
status
notes
terms
```

---

### invoice_items

```
id
invoice_id
item_name
description
quantity
unit_price
subtotal
```

---

### payment_methods

```
id
user_id
bank_name
account_number
account_holder
qris_image
notes
```

---

# 18. Future Features (Optional)

Fitur tambahan yang bisa dikembangkan:

### 1. Public Invoice Link

Invoice bisa dibuka melalui link:

```
invoice.app/i/INV-2026-001
```

---

### 2. Payment Integration

* Midtrans
* Stripe
* Xendit

---

### 3. Multi Currency

Support:

* IDR
* USD
* SGD

---

### 4. Recurring Invoice

Invoice otomatis setiap bulan.

---

### 5. Template Invoice

User bisa memilih template:

* Minimal
* Corporate
* Modern

---

# 19. Success Metrics

Keberhasilan aplikasi diukur dari:

* jumlah invoice dibuat
* jumlah user aktif
* conversion ke PDF download


All 63 tests pass. Let me provide a summary of everything that was built.                                                                                                                               
                                                                                                                                                                                                          
  ---                                                                                                                                                                                                     
  Summary                                                                                                                                                                                                 
   
  Here's what was implemented for the Invoice Generator app:                                                                                                                                              
                                                                                                                                                                                                        
  Database & Models

  - 5 migrations: companies, customers, payment_methods, invoices, invoice_items
  - 5 models with relationships, fillable fields, casts, and factories
  - User model updated with company(), customers(), paymentMethods(), invoices() relationships
  - Invoice model includes generateInvoiceNumber() (auto-incrementing INV-YYYY-NNNN format) and recalculate() for totals

  Pages (Single-File Livewire Components)

  ┌─────────────────┬─────────────────────────────────────────────────────────────────────────────────────┐
  │     Feature     │                                        Pages                                        │
  ├─────────────────┼─────────────────────────────────────────────────────────────────────────────────────┤
  │ Dashboard       │ Stats cards (total invoices, revenue, unpaid, overdue) + recent invoices table      │
  ├─────────────────┼─────────────────────────────────────────────────────────────────────────────────────┤
  │ Customers       │ List with search, Create/Edit form, Delete with confirmation                        │
  ├─────────────────┼─────────────────────────────────────────────────────────────────────────────────────┤
  │ Company Profile │ Edit form with logo & signature upload                                              │
  ├─────────────────┼─────────────────────────────────────────────────────────────────────────────────────┤
  │ Payment Methods │ Card-style list, Create/Edit form with QRIS image upload                            │
  ├─────────────────┼─────────────────────────────────────────────────────────────────────────────────────┤
  │ Invoices        │ List with search & status filter, Create/Edit form with dynamic items, Preview page │
  └─────────────────┴─────────────────────────────────────────────────────────────────────────────────────┘

  Invoice Features

  - Dynamic item rows (add/remove) with auto-calculated subtotals
  - Payment summary with discount, tax %, additional fee, and grand total
  - Status management (draft, sent, paid, overdue)
  - Customer & payment method selection
  - Notes & terms fields
  - Multi-currency support (IDR, USD, SGD)

  PDF Export

  - barryvdh/laravel-dompdf installed
  - Professional PDF template with company info, logo, items table, totals, payment details, signature
  - Download via InvoicePdfController

  Sidebar Navigation

  Updated with: Dashboard, Invoices, Customers, Company Profile, Payment Methods

  Tests

  - 30 new tests (63 total, all passing) covering:
    - Customer CRUD + authorization
    - Company profile create/update + validation
    - Payment method CRUD + validation
    - Invoice CRUD, auto-numbering, status changes, PDF download, recalculation, authorization