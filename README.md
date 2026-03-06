# Invoice Generator

A multi-tenant web application for creating, managing, and exporting professional invoices as PDF. Built with Laravel 12, Livewire 4, and Flux UI.

## Features

- **Multi-Tenant Data Isolation** - Each user only sees their own data (invoices, customers, payment methods, company profile)
- **Super Admin** - Dedicated superadmin role with access to all records across all users
- **Authentication** - Register, login, password reset, two-factor authentication (via Laravel Fortify)
- **Company Profile** - Store business info, logo, and signature that appear on invoices
- **Customer Management** - CRUD for client records with search functionality
- **Invoice Management** - Create invoices with dynamic line items, tax, discount, and additional fees
- **Auto Invoice Numbering** - Sequential format `INV-YYYY-NNNN`, resets yearly
- **Payment Methods** - Manage bank accounts and QRIS payment options
- **PDF Export** - Download professional invoices as PDF
- **Dashboard** - Overview with total invoices, revenue, unpaid, overdue stats
- **Status Tracking** - Draft, Sent, Paid, Overdue statuses
- **Landing Page** - Public landing page with feature overview and step-by-step usage guide

## Tech Stack

- **Backend**: Laravel 12, PHP 8.4
- **Frontend**: Livewire 4 (single-file components), Flux UI Free, Tailwind CSS 4
- **Auth**: Laravel Fortify
- **PDF**: barryvdh/laravel-dompdf
- **Testing**: Pest 4
- **Server**: Laravel Herd

## Requirements

- PHP 8.4+
- Composer
- Node.js & npm
- MySQL

## Installation

```bash
# Clone the repository
git clone <repository-url>
cd trial-invoice-generator

# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database
php artisan migrate

# Seed superadmin user
php artisan db:seed --class=SuperAdminSeeder

# Storage link (for logo, signature, QRIS uploads)
php artisan storage:link

# Build frontend assets
npm run build
```

## Super Admin

A superadmin account can be created via the seeder:

```bash
php artisan db:seed --class=SuperAdminSeeder
```

Default credentials:
- **Email**: `admin@admin.com`
- **Password**: `password`

The superadmin can view, edit, and manage all records from all users. Regular users can only access their own data.

## Development

```bash
# Start development server (if not using Laravel Herd)
composer run dev

# Or run individually
php artisan serve
npm run dev
```

Application URL: http://trial-invoice-generator.test (via Laravel Herd)

## Testing

```bash
# Run all tests
php artisan test

# Run with compact output
php artisan test --compact

# Run specific test file
php artisan test tests/Feature/InvoiceTest.php

# Run filtered tests
php artisan test --filter=invoice
```

## Project Structure

```
app/
  Models/          # User, Company, Customer, Invoice, InvoiceItem, PaymentMethod
  Http/Controllers # InvoicePdfController
resources/views/
  welcome.blade.php # Public landing page
  pages/           # Livewire single-file components (dashboard, invoices, customers, etc.)
  layouts/         # App layout with sidebar navigation
  pdf/             # Invoice PDF template
database/
  migrations/      # Table schemas
  factories/       # Model factories for testing
  seeders/         # SuperAdminSeeder
tests/Feature/     # Feature tests for all modules
```

## License

This project is open-sourced software.
