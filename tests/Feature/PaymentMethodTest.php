<?php

use App\Models\PaymentMethod;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

test('payment methods index page is accessible', function () {
    $this->get(route('payment-methods.index'))->assertOk();
});

test('payment method can be created', function () {
    Livewire::test('pages::payment-methods.form')
        ->set('bank_name', 'BCA')
        ->set('account_number', '1234567890')
        ->set('account_holder', 'John Doe')
        ->call('save');

    $this->assertDatabaseHas('payment_methods', [
        'user_id' => $this->user->id,
        'bank_name' => 'BCA',
        'account_number' => '1234567890',
    ]);
});

test('payment method fields are required', function () {
    Livewire::test('pages::payment-methods.form')
        ->set('bank_name', '')
        ->set('account_number', '')
        ->set('account_holder', '')
        ->call('save')
        ->assertHasErrors(['bank_name', 'account_number', 'account_holder']);
});

test('payment method can be updated', function () {
    $method = PaymentMethod::factory()->create(['user_id' => $this->user->id]);

    Livewire::test('pages::payment-methods.form', ['paymentMethod' => $method])
        ->set('bank_name', 'BNI')
        ->call('save');

    $this->assertDatabaseHas('payment_methods', [
        'id' => $method->id,
        'bank_name' => 'BNI',
    ]);
});

test('payment method can be deleted', function () {
    $method = PaymentMethod::factory()->create(['user_id' => $this->user->id]);

    Livewire::test('pages::payment-methods.index')
        ->call('deletePaymentMethod', $method->id);

    $this->assertDatabaseMissing('payment_methods', ['id' => $method->id]);
});
