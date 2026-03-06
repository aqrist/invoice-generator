<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Payment Methods')] class extends Component {
    public function deletePaymentMethod(int $paymentMethodId): void
    {
        Auth::user()->paymentMethods()->findOrFail($paymentMethodId)->delete();
    }

    public function with(): array
    {
        return [
            'paymentMethods' => Auth::user()->paymentMethods()->latest()->get(),
        ];
    }
}; ?>

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <flux:heading size="xl">{{ __('Payment Methods') }}</flux:heading>
            <flux:button variant="primary" icon="plus" :href="route('payment-methods.create')" wire:navigate>
                {{ __('Add Payment Method') }}
            </flux:button>
        </div>

        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            @forelse ($paymentMethods as $method)
                <div wire:key="pm-{{ $method->id }}" class="rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                    <div class="flex items-start justify-between">
                        <div>
                            <flux:heading size="lg">{{ $method->bank_name }}</flux:heading>
                            <flux:text class="mt-1">{{ $method->account_number }}</flux:text>
                            <flux:text>{{ $method->account_holder }}</flux:text>
                            @if ($method->notes)
                                <flux:text class="mt-2 text-xs">{{ $method->notes }}</flux:text>
                            @endif
                        </div>
                        <div class="flex gap-1">
                            <flux:button size="sm" variant="ghost" icon="pencil-square" :href="route('payment-methods.edit', $method)" wire:navigate />
                            <flux:button size="sm" variant="ghost" icon="trash" wire:click="deletePaymentMethod({{ $method->id }})" wire:confirm="{{ __('Are you sure?') }}" />
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center">
                    <flux:text>{{ __('No payment methods yet.') }}</flux:text>
                </div>
            @endforelse
        </div>
    </div>
