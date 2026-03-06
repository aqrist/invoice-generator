<?php

use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

new #[Title('Payment Method')] class extends Component {
    use WithFileUploads;

    public ?PaymentMethod $paymentMethod = null;

    public string $bank_name = '';
    public string $account_number = '';
    public string $account_holder = '';
    public string $notes = '';
    public $qris_image = null;
    public ?string $existingQrisImage = null;

    public function mount(?PaymentMethod $paymentMethod = null): void
    {
        if ($paymentMethod?->exists) {
            abort_unless($paymentMethod->user_id === Auth::id(), 403);
            $this->paymentMethod = $paymentMethod;
            $this->bank_name = $paymentMethod->bank_name;
            $this->account_number = $paymentMethod->account_number;
            $this->account_holder = $paymentMethod->account_holder;
            $this->notes = $paymentMethod->notes ?? '';
            $this->existingQrisImage = $paymentMethod->qris_image;
        }
    }

    public function save(): void
    {
        $validated = $this->validate([
            'bank_name' => ['required', 'string', 'max:255'],
            'account_number' => ['required', 'string', 'max:50'],
            'account_holder' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'qris_image' => ['nullable', 'image', 'max:2048'],
        ]);

        $data = collect($validated)->except('qris_image')->toArray();

        if ($this->qris_image) {
            if ($this->existingQrisImage) {
                Storage::disk('public')->delete($this->existingQrisImage);
            }
            $data['qris_image'] = $this->qris_image->store('qris', 'public');
        }

        if ($this->paymentMethod?->exists) {
            $this->paymentMethod->update($data);
        } else {
            Auth::user()->paymentMethods()->create($data);
        }

        $this->redirect(route('payment-methods.index'), navigate: true);
    }
}; ?>

    <div class="max-w-2xl space-y-6">
        <flux:heading size="xl">
            {{ $paymentMethod?->exists ? __('Edit Payment Method') : __('Add Payment Method') }}
        </flux:heading>

        <form wire:submit="save" class="space-y-6">
            <flux:input wire:model="bank_name" :label="__('Bank Name')" required />
            <flux:input wire:model="account_number" :label="__('Account Number')" required />
            <flux:input wire:model="account_holder" :label="__('Account Holder')" required />
            <flux:textarea wire:model="notes" :label="__('Notes')" rows="3" />

            <div>
                <flux:field>
                    <flux:label>{{ __('QRIS Image') }}</flux:label>
                    <input type="file" wire:model="qris_image" accept="image/*" class="block w-full text-sm text-zinc-500 file:mr-4 file:rounded file:border-0 file:bg-zinc-100 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-zinc-700 hover:file:bg-zinc-200 dark:text-zinc-400 dark:file:bg-zinc-700 dark:file:text-zinc-300" />
                    <flux:error name="qris_image" />
                </flux:field>
                @if ($existingQrisImage)
                    <img src="{{ Storage::url($existingQrisImage) }}" alt="QRIS" class="mt-2 h-32 rounded" />
                @endif
            </div>

            <div class="flex items-center gap-4">
                <flux:button variant="primary" type="submit">{{ __('Save') }}</flux:button>
                <flux:button variant="ghost" :href="route('payment-methods.index')" wire:navigate>{{ __('Cancel') }}</flux:button>
            </div>
        </form>
    </div>
