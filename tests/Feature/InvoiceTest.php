<?php

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

test('invoices index page is accessible', function () {
    $this->get(route('invoices.index'))->assertOk();
});

test('invoice create page is accessible', function () {
    $this->get(route('invoices.create'))->assertOk();
});

test('invoice can be created', function () {
    $customer = Customer::factory()->create(['user_id' => $this->user->id]);

    Livewire::test('pages::invoices.form')
        ->set('customer_id', $customer->id)
        ->set('invoice_date', '2026-03-06')
        ->set('due_date', '2026-04-06')
        ->set('items', [
            ['item_name' => 'Web Development', 'description' => 'Homepage', 'quantity' => 1, 'unit_price' => 5000000, 'subtotal' => 5000000],
        ])
        ->call('save');

    $this->assertDatabaseHas('invoices', [
        'user_id' => $this->user->id,
        'customer_id' => $customer->id,
    ]);

    $this->assertDatabaseHas('invoice_items', [
        'item_name' => 'Web Development',
        'quantity' => 1,
        'unit_price' => 5000000,
    ]);
});

test('invoice requires at least one item', function () {
    Livewire::test('pages::invoices.form')
        ->set('items', [])
        ->call('save')
        ->assertHasErrors(['items']);
});

test('invoice number is auto generated', function () {
    $number = Invoice::generateInvoiceNumber();
    expect($number)->toStartWith('INV-'.now()->year.'-');
});

test('invoice number increments correctly', function () {
    Invoice::factory()->create([
        'user_id' => $this->user->id,
        'invoice_number' => 'INV-'.now()->year.'-0001',
    ]);

    $next = Invoice::generateInvoiceNumber();
    expect($next)->toBe('INV-'.now()->year.'-0002');
});

test('invoice show page is accessible', function () {
    $invoice = Invoice::factory()->create(['user_id' => $this->user->id]);

    $this->get(route('invoices.show', $invoice))->assertOk();
});

test('invoice can be updated', function () {
    $invoice = Invoice::factory()->create(['user_id' => $this->user->id]);
    InvoiceItem::factory()->create(['invoice_id' => $invoice->id]);

    Livewire::test('pages::invoices.form', ['invoice' => $invoice])
        ->set('notes', 'Updated notes')
        ->set('items', [
            ['item_name' => 'Updated Item', 'description' => '', 'quantity' => 2, 'unit_price' => 1000000, 'subtotal' => 2000000],
        ])
        ->call('save');

    $this->assertDatabaseHas('invoices', [
        'id' => $invoice->id,
        'notes' => 'Updated notes',
    ]);
});

test('invoice can be deleted', function () {
    $invoice = Invoice::factory()->create(['user_id' => $this->user->id]);

    Livewire::test('pages::invoices.index')
        ->call('deleteInvoice', $invoice->id);

    $this->assertDatabaseMissing('invoices', ['id' => $invoice->id]);
});

test('invoice status can be changed', function () {
    $invoice = Invoice::factory()->create(['user_id' => $this->user->id, 'status' => 'draft']);

    Livewire::test('pages::invoices.index')
        ->call('markAs', $invoice->id, 'paid');

    $this->assertDatabaseHas('invoices', [
        'id' => $invoice->id,
        'status' => 'paid',
    ]);
});

test('invoice pdf can be downloaded', function () {
    $invoice = Invoice::factory()->create(['user_id' => $this->user->id]);
    InvoiceItem::factory()->create(['invoice_id' => $invoice->id]);

    $this->get(route('invoices.pdf', $invoice))->assertOk();
});

test('user cannot access another users invoice', function () {
    $otherUser = User::factory()->create();
    $invoice = Invoice::factory()->create(['user_id' => $otherUser->id]);

    $this->get(route('invoices.show', $invoice))->assertForbidden();
});

test('invoice recalculates totals correctly', function () {
    $invoice = Invoice::factory()->create([
        'user_id' => $this->user->id,
        'discount' => 100000,
        'tax_percentage' => 11,
        'additional_fee' => 50000,
    ]);

    InvoiceItem::factory()->create(['invoice_id' => $invoice->id, 'subtotal' => 1000000]);
    InvoiceItem::factory()->create(['invoice_id' => $invoice->id, 'subtotal' => 2000000]);

    $invoice->recalculate();

    expect((float) $invoice->subtotal)->toBe(3000000.00)
        ->and((float) $invoice->tax_amount)->toBe(330000.00)
        ->and((float) $invoice->grand_total)->toBe(3280000.00);
});
