<?php

use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Customers')] class extends Component {
    public string $search = '';

    private function customerQuery(): \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\HasMany
    {
        return Auth::user()->isSuperAdmin()
            ? Customer::query()
            : Auth::user()->customers();
    }

    public function deleteCustomer(int $customerId): void
    {
        $this->customerQuery()->findOrFail($customerId)->delete();
    }

    public function with(): array
    {
        return [
            'customers' => $this->customerQuery()
                ->when($this->search, fn ($query) => $query->where('name', 'like', "%{$this->search}%")
                    ->orWhere('company', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%"))
                ->latest()
                ->paginate(10),
        ];
    }
}; ?>

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <flux:heading size="xl">{{ __('Customers') }}</flux:heading>
            <flux:button variant="primary" icon="plus" :href="route('customers.create')" wire:navigate>
                {{ __('Add Customer') }}
            </flux:button>
        </div>

        <flux:input wire:model.live.debounce.300ms="search" icon="magnifying-glass" :placeholder="__('Search customers...')" />

        <div class="overflow-x-auto rounded-lg border border-zinc-200 dark:border-zinc-700">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">{{ __('Name') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">{{ __('Company') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">{{ __('Email') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">{{ __('Phone') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-700 dark:bg-zinc-900">
                    @forelse ($customers as $customer)
                        <tr wire:key="customer-{{ $customer->id }}">
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-zinc-900 dark:text-zinc-100">{{ $customer->name }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-zinc-500 dark:text-zinc-400">{{ $customer->company ?? '-' }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-zinc-500 dark:text-zinc-400">{{ $customer->email ?? '-' }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-zinc-500 dark:text-zinc-400">{{ $customer->phone ?? '-' }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-right text-sm">
                                <flux:button size="sm" variant="ghost" icon="pencil-square" :href="route('customers.edit', $customer)" wire:navigate />
                                <flux:button size="sm" variant="ghost" icon="trash" wire:click="deleteCustomer({{ $customer->id }})" wire:confirm="{{ __('Are you sure you want to delete this customer?') }}" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-sm text-zinc-500 dark:text-zinc-400">
                                {{ __('No customers found.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>{{ $customers->links() }}</div>
    </div>
