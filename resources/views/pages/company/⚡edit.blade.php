<?php

use App\Models\Company;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

new #[Title('Company Profile')] class extends Component {
    use WithFileUploads;

    public string $company_name = '';
    public string $address = '';
    public string $phone = '';
    public string $email = '';
    public string $website = '';
    public string $tax_number = '';

    public $logo = null;
    public $signature = null;
    public ?string $existingLogo = null;
    public ?string $existingSignature = null;

    public function mount(): void
    {
        $company = Auth::user()->company;

        if ($company) {
            $this->company_name = $company->company_name;
            $this->address = $company->address ?? '';
            $this->phone = $company->phone ?? '';
            $this->email = $company->email ?? '';
            $this->website = $company->website ?? '';
            $this->tax_number = $company->tax_number ?? '';
            $this->existingLogo = $company->logo;
            $this->existingSignature = $company->signature;
        }
    }

    public function save(): void
    {
        $validated = $this->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:1000'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'website' => ['nullable', 'string', 'max:255'],
            'tax_number' => ['nullable', 'string', 'max:50'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'signature' => ['nullable', 'image', 'max:2048'],
        ]);

        $data = collect($validated)->except(['logo', 'signature'])->toArray();

        if ($this->logo) {
            if ($this->existingLogo) {
                Storage::disk('public')->delete($this->existingLogo);
            }
            $data['logo'] = $this->logo->store('logos', 'public');
        }

        if ($this->signature) {
            if ($this->existingSignature) {
                Storage::disk('public')->delete($this->existingSignature);
            }
            $data['signature'] = $this->signature->store('signatures', 'public');
        }

        Auth::user()->company()->updateOrCreate(
            ['user_id' => Auth::id()],
            $data,
        );

        $this->dispatch('company-updated');
    }
}; ?>

    <div class="max-w-2xl space-y-6">
        <flux:heading size="xl">{{ __('Company Profile') }}</flux:heading>
        <flux:text>{{ __('This information will appear on your invoices.') }}</flux:text>

        <form wire:submit="save" class="space-y-6">
            <flux:input wire:model="company_name" :label="__('Company Name')" required />
            <flux:textarea wire:model="address" :label="__('Address')" rows="3" />
            <flux:input wire:model="phone" :label="__('Phone')" />
            <flux:input wire:model="email" :label="__('Email')" type="email" />
            <flux:input wire:model="website" :label="__('Website')" />
            <flux:input wire:model="tax_number" :label="__('Tax Number (NPWP)')" />

            <div>
                <flux:field>
                    <flux:label>{{ __('Logo') }}</flux:label>
                    <input type="file" wire:model="logo" accept="image/*" class="block w-full text-sm text-zinc-500 file:mr-4 file:rounded file:border-0 file:bg-zinc-100 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-zinc-700 hover:file:bg-zinc-200 dark:text-zinc-400 dark:file:bg-zinc-700 dark:file:text-zinc-300" />
                    <flux:error name="logo" />
                </flux:field>
                @if ($existingLogo)
                    <img src="{{ Storage::url($existingLogo) }}" alt="Logo" class="mt-2 h-16 rounded" />
                @endif
            </div>

            <div>
                <flux:field>
                    <flux:label>{{ __('Signature') }}</flux:label>
                    <input type="file" wire:model="signature" accept="image/*" class="block w-full text-sm text-zinc-500 file:mr-4 file:rounded file:border-0 file:bg-zinc-100 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-zinc-700 hover:file:bg-zinc-200 dark:text-zinc-400 dark:file:bg-zinc-700 dark:file:text-zinc-300" />
                    <flux:error name="signature" />
                </flux:field>
                @if ($existingSignature)
                    <img src="{{ Storage::url($existingSignature) }}" alt="Signature" class="mt-2 h-16 rounded" />
                @endif
            </div>

            <div class="flex items-center gap-4">
                <flux:button variant="primary" type="submit">{{ __('Save') }}</flux:button>
                <x-action-message on="company-updated">{{ __('Saved.') }}</x-action-message>
            </div>
        </form>
    </div>
