<?php

use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Invoices')] class extends Component {
    public string $search = '';
    public string $statusFilter = '';

    public function deleteInvoice(int $invoiceId): void
    {
        Auth::user()->invoices()->findOrFail($invoiceId)->delete();
    }

    public function markAs(int $invoiceId, string $status): void
    {
        $invoice = Auth::user()->invoices()->findOrFail($invoiceId);
        $invoice->update(['status' => $status]);
    }

    public function with(): array
    {
        return [
            'invoices' => Auth::user()->invoices()
                ->with('customer')
                ->when($this->search, fn ($query) => $query->where('invoice_number', 'like', "%{$this->search}%")
                    ->orWhereHas('customer', fn ($q) => $q->where('name', 'like', "%{$this->search}%")))
                ->when($this->statusFilter, fn ($query) => $query->where('status', $this->statusFilter))
                ->latest('invoice_date')
                ->paginate(10),
        ];
    }
}; ?>

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <flux:heading size="xl">{{ __('Invoices') }}</flux:heading>
            <flux:button variant="primary" icon="plus" :href="route('invoices.create')" wire:navigate>
                {{ __('New Invoice') }}
            </flux:button>
        </div>

        <div class="flex flex-col gap-4 sm:flex-row">
            <div class="flex-1">
                <flux:input wire:model.live.debounce.300ms="search" icon="magnifying-glass" :placeholder="__('Search invoices...')" />
            </div>
            <flux:select wire:model.live="statusFilter" class="w-full sm:w-48">
                <option value="">{{ __('All Status') }}</option>
                <option value="draft">{{ __('Draft') }}</option>
                <option value="sent">{{ __('Sent') }}</option>
                <option value="paid">{{ __('Paid') }}</option>
                <option value="overdue">{{ __('Overdue') }}</option>
            </flux:select>
        </div>

        <div class="overflow-x-auto rounded-lg border border-zinc-200 dark:border-zinc-700">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">{{ __('Invoice #') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">{{ __('Customer') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">{{ __('Date') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">{{ __('Due Date') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">{{ __('Total') }}</th>
                        <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">{{ __('Status') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-700 dark:bg-zinc-900">
                    @forelse ($invoices as $invoice)
                        <tr wire:key="invoice-{{ $invoice->id }}">
                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                {{ $invoice->invoice_number }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-zinc-500 dark:text-zinc-400">
                                {{ $invoice->customer?->name ?? '-' }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-zinc-500 dark:text-zinc-400">
                                {{ $invoice->invoice_date->format('d M Y') }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-zinc-500 dark:text-zinc-400">
                                {{ $invoice->due_date?->format('d M Y') ?? '-' }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right text-sm text-zinc-900 dark:text-zinc-100">
                                {{ $invoice->currency }} {{ number_format($invoice->grand_total, 2) }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-center text-sm">
                                @php
                                    $statusColors = [
                                        'draft' => 'zinc',
                                        'sent' => 'blue',
                                        'paid' => 'green',
                                        'overdue' => 'red',
                                    ];
                                @endphp
                                <flux:badge :color="$statusColors[$invoice->status] ?? 'zinc'" size="sm">
                                    {{ ucfirst($invoice->status) }}
                                </flux:badge>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right text-sm">
                                <flux:dropdown>
                                    <flux:button size="sm" variant="ghost" icon="ellipsis-vertical" />
                                    <flux:menu>
                                        <flux:menu.item icon="eye" :href="route('invoices.show', $invoice)" wire:navigate>{{ __('View') }}</flux:menu.item>
                                        <flux:menu.item icon="pencil-square" :href="route('invoices.edit', $invoice)" wire:navigate>{{ __('Edit') }}</flux:menu.item>
                                        <flux:menu.item icon="arrow-down-tray" :href="route('invoices.pdf', $invoice)">{{ __('Download PDF') }}</flux:menu.item>
                                        <flux:menu.separator />
                                        <flux:menu.item icon="paper-airplane" wire:click="markAs({{ $invoice->id }}, 'sent')">{{ __('Mark as Sent') }}</flux:menu.item>
                                        <flux:menu.item icon="check-circle" wire:click="markAs({{ $invoice->id }}, 'paid')">{{ __('Mark as Paid') }}</flux:menu.item>
                                        <flux:menu.separator />
                                        <flux:menu.item icon="trash" variant="danger" wire:click="deleteInvoice({{ $invoice->id }})" wire:confirm="{{ __('Are you sure?') }}">{{ __('Delete') }}</flux:menu.item>
                                    </flux:menu>
                                </flux:dropdown>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-sm text-zinc-500 dark:text-zinc-400">
                                {{ __('No invoices found.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>{{ $invoices->links() }}</div>
    </div>
