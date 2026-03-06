# Invoice Generator

A web application for creating, managing, and exporting professional invoices as PDF. Built with Laravel 12, Livewire 4, and Flux UI.

## Features

- **Authentication** - Register, login, password reset, two-factor authentication (via Laravel Fortify)
- **Company Profile** - Store business info, logo, and signature that appear on invoices
- **Customer Management** - CRUD for client records with search functionality
- **Invoice Management** - Create invoices with dynamic line items, tax, discount, and additional fees
- **Auto Invoice Numbering** - Sequential format `INV-YYYY-NNNN`, resets yearly
- **Payment Methods** - Manage bank accounts and QRIS payment options
- **PDF Export** - Download professional invoices as PDF
- **Dashboard** - Overview with total invoices, revenue, unpaid, overdue stats
- **Status Tracking** - Draft, Sent, Paid, Overdue statuses

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

# Storage link (for logo, signature, QRIS uploads)
php artisan storage:link

# Build frontend assets
npm run build
```

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
  pages/           # Livewire single-file components (dashboard, invoices, customers, etc.)
  layouts/         # App layout with sidebar navigation
  pdf/             # Invoice PDF template
database/
  migrations/      # Table schemas
  factories/       # Model factories for testing
tests/Feature/     # Feature tests for all modules
```

## License

This project is open-sourced software.
