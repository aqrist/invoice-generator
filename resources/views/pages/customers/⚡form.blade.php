<?php

use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Customer')] class extends Component {
    public ?Customer $customer = null;

    public string $name = '';
    public string $company = '';
    public string $address = '';
    public string $email = '';
    public string $phone = '';
    public string $contact_person = '';
    public string $notes = '';

    public function mount(?Customer $customer = null): void
    {
        if ($customer?->exists) {
            abort_unless($customer->user_id === Auth::id() || Auth::user()->isSuperAdmin(), 403);
            $this->customer = $customer;
            $this->name = $customer->name;
            $this->company = $customer->company ?? '';
            $this->address = $customer->address ?? '';
            $this->email = $customer->email ?? '';
            $this->phone = $customer->phone ?? '';
            $this->contact_person = $customer->contact_person ?? '';
            $this->notes = $customer->notes ?? '';
        }
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'company' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:1000'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($this->customer?->exists) {
            $this->customer->update($validated);
        } else {
            Auth::user()->customers()->create($validated);
        }

        $this->redirect(route('customers.index'), navigate: true);
    }
}; ?>

    <div class="max-w-2xl space-y-6">
        <flux:heading size="xl">
            {{ $customer?->exists ? __('Edit Customer') : __('Add Customer') }}
        </flux:heading>

        <form wire:submit="save" class="space-y-6">
            <flux:input wire:model="name" :label="__('Name')" required />
            <flux:input wire:model="company" :label="__('Company')" />
            <flux:textarea wire:model="address" :label="__('Address')" rows="3" />
            <flux:input wire:model="email" :label="__('Email')" type="email" />
            <flux:input wire:model="phone" :label="__('Phone')" />
            <flux:input wire:model="contact_person" :label="__('Contact Person')" />
            <flux:textarea wire:model="notes" :label="__('Notes')" rows="3" />

            <div class="flex items-center gap-4">
                <flux:button variant="primary" type="submit">{{ __('Save') }}</flux:button>
                <flux:button variant="ghost" :href="route('customers.index')" wire:navigate>{{ __('Cancel') }}</flux:button>
            </div>
        </form>
    </div>
