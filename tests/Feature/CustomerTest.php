<?php

use App\Models\Customer;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

test('customers index page is accessible', function () {
    $this->get(route('customers.index'))->assertOk();
});

test('customers index shows customer list', function () {
    $customer = Customer::factory()->create(['user_id' => $this->user->id, 'name' => 'John Doe']);

    $this->get(route('customers.index'))->assertOk()->assertSee('John Doe');
});

test('customer create page is accessible', function () {
    $this->get(route('customers.create'))->assertOk();
});

test('customer can be created', function () {
    Livewire::test('pages::customers.form')
        ->set('name', 'Jane Doe')
        ->set('company', 'Acme Corp')
        ->set('email', 'jane@acme.com')
        ->set('phone', '08123456789')
        ->call('save');

    $this->assertDatabaseHas('customers', [
        'user_id' => $this->user->id,
        'name' => 'Jane Doe',
        'company' => 'Acme Corp',
    ]);
});

test('customer name is required', function () {
    Livewire::test('pages::customers.form')
        ->set('name', '')
        ->call('save')
        ->assertHasErrors(['name']);
});

test('customer can be updated', function () {
    $customer = Customer::factory()->create(['user_id' => $this->user->id]);

    Livewire::test('pages::customers.form', ['customer' => $customer])
        ->set('name', 'Updated Name')
        ->call('save');

    $this->assertDatabaseHas('customers', [
        'id' => $customer->id,
        'name' => 'Updated Name',
    ]);
});

test('customer can be deleted', function () {
    $customer = Customer::factory()->create(['user_id' => $this->user->id]);

    Livewire::test('pages::customers.index')
        ->call('deleteCustomer', $customer->id);

    $this->assertDatabaseMissing('customers', ['id' => $customer->id]);
});

test('user cannot access another users customer', function () {
    $otherUser = User::factory()->create();
    $customer = Customer::factory()->create(['user_id' => $otherUser->id]);

    $this->get(route('customers.edit', $customer))->assertForbidden();
});
