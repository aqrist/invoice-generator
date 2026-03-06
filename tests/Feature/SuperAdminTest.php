<?php

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\PaymentMethod;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->superAdmin()->create();
    $this->regularUser = User::factory()->create();
    $this->actingAs($this->admin);
});

test('superadmin can access dashboard', function () {
    $this->get(route('dashboard'))->assertOk();
});

test('superadmin can see all invoices', function () {
    Invoice::factory()->create(['user_id' => $this->regularUser->id]);
    Invoice::factory()->create(['user_id' => $this->admin->id]);

    $this->get(route('invoices.index'))->assertOk();
});

test('superadmin can view other users invoice', function () {
    $invoice = Invoice::factory()->create(['user_id' => $this->regularUser->id]);

    $this->get(route('invoices.show', $invoice))->assertOk();
});

test('superadmin can edit other users invoice', function () {
    $invoice = Invoice::factory()->create(['user_id' => $this->regularUser->id]);

    $this->get(route('invoices.edit', $invoice))->assertOk();
});

test('superadmin can download other users invoice pdf', function () {
    $invoice = Invoice::factory()->create(['user_id' => $this->regularUser->id]);

    $this->get(route('invoices.pdf', $invoice))->assertOk();
});

test('superadmin can see all customers', function () {
    Customer::factory()->count(3)->create(['user_id' => $this->regularUser->id]);

    $this->get(route('customers.index'))->assertOk();
});

test('superadmin can edit other users customer', function () {
    $customer = Customer::factory()->create(['user_id' => $this->regularUser->id]);

    $this->get(route('customers.edit', $customer))->assertOk();
});

test('superadmin can see all payment methods', function () {
    PaymentMethod::factory()->count(2)->create(['user_id' => $this->regularUser->id]);

    $this->get(route('payment-methods.index'))->assertOk();
});

test('superadmin can edit other users payment method', function () {
    $paymentMethod = PaymentMethod::factory()->create(['user_id' => $this->regularUser->id]);

    $this->get(route('payment-methods.edit', $paymentMethod))->assertOk();
});

test('regular user cannot see other users invoice', function () {
    $this->actingAs($this->regularUser);
    $invoice = Invoice::factory()->create(['user_id' => $this->admin->id]);

    $this->get(route('invoices.show', $invoice))->assertForbidden();
});

test('regular user cannot edit other users customer', function () {
    $this->actingAs($this->regularUser);
    $customer = Customer::factory()->create(['user_id' => $this->admin->id]);

    $this->get(route('customers.edit', $customer))->assertForbidden();
});

test('is_superadmin defaults to false', function () {
    $user = User::factory()->create();

    expect($user->isSuperAdmin())->toBeFalse();
});
