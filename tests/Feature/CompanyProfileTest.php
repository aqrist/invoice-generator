<?php

use App\Models\Company;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

test('company profile page is accessible', function () {
    $this->get(route('company.edit'))->assertOk();
});

test('company profile can be created', function () {
    Livewire::test('pages::company.edit')
        ->set('company_name', 'My Company')
        ->set('address', '123 Street')
        ->set('phone', '08123456789')
        ->set('email', 'info@company.com')
        ->set('tax_number', '12.345.678.9-012.345')
        ->call('save');

    $this->assertDatabaseHas('companies', [
        'user_id' => $this->user->id,
        'company_name' => 'My Company',
        'tax_number' => '12.345.678.9-012.345',
    ]);
});

test('company profile can be updated', function () {
    Company::factory()->create(['user_id' => $this->user->id, 'company_name' => 'Old Name']);

    Livewire::test('pages::company.edit')
        ->set('company_name', 'New Name')
        ->call('save');

    $this->assertDatabaseHas('companies', [
        'user_id' => $this->user->id,
        'company_name' => 'New Name',
    ]);
});

test('company name is required', function () {
    Livewire::test('pages::company.edit')
        ->set('company_name', '')
        ->call('save')
        ->assertHasErrors(['company_name']);
});
