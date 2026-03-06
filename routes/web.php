<?php

use App\Http\Controllers\InvoicePdfController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::livewire('dashboard', 'pages::dashboard')->name('dashboard');

    // Customers
    Route::livewire('customers', 'pages::customers.index')->name('customers.index');
    Route::livewire('customers/create', 'pages::customers.form')->name('customers.create');
    Route::livewire('customers/{customer}/edit', 'pages::customers.form')->name('customers.edit');

    // Company Profile
    Route::livewire('company', 'pages::company.edit')->name('company.edit');

    // Payment Methods
    Route::livewire('payment-methods', 'pages::payment-methods.index')->name('payment-methods.index');
    Route::livewire('payment-methods/create', 'pages::payment-methods.form')->name('payment-methods.create');
    Route::livewire('payment-methods/{paymentMethod}/edit', 'pages::payment-methods.form')->name('payment-methods.edit');

    // Invoices
    Route::livewire('invoices', 'pages::invoices.index')->name('invoices.index');
    Route::livewire('invoices/create', 'pages::invoices.form')->name('invoices.create');
    Route::livewire('invoices/{invoice}', 'pages::invoices.show')->name('invoices.show');
    Route::livewire('invoices/{invoice}/edit', 'pages::invoices.form')->name('invoices.edit');
    Route::get('invoices/{invoice}/pdf', InvoicePdfController::class)->name('invoices.pdf');
});

require __DIR__.'/settings.php';
