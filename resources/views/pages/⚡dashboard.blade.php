<?php

use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Dashboard')] class extends Component {
    public function with(): array
    {
        $user = Auth::user();

        return [
            'totalInvoices' => $user->invoices()->count(),
            'totalRevenue' => $user->invoices()->where('status', 'paid')->sum('grand_total'),
            'unpaidInvoices' => $user->invoices()->whereIn('status', ['sent', 'draft'])->count(),
            'overdueInvoices' => $user->invoices()->where('status', 'overdue')->count(),
            'recentInvoices' => $user->invoices()->with('customer')->latest('invoice_date')->limit(5)->get(),
        ];
    }
}; ?>

<div class="space-y-6">
        <flux:heading size="xl">{{ __('Dashboard') }}</flux:heading>

        {{-- Stats Cards --}}
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <flux:text class="text-sm uppercase text-zinc-500">{{ __('Total Invoices') }}</flux:text>
                <flux:heading size="xl" class="mt-2">{{ $totalInvoices }}</flux:heading>
            </div>
            <div class="rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <flux:text class="text-sm uppercase text-zinc-500">{{ __('Total Revenue') }}</flux:text>
                <flux:heading size="xl" class="mt-2">IDR {{ number_format($totalRevenue, 0) }}</flux:heading>
            </div>
            <div class="rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <flux:text class="text-sm uppercase text-zinc-500">{{ __('Unpaid') }}</flux:text>
                <flux:heading size="xl" class="mt-2 text-yellow-600">{{ $unpaidInvoices }}</flux:heading>
            </div>
            <div class="rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <flux:text class="text-sm uppercase text-zinc-500">{{ __('Overdue') }}</flux:text>
                <flux:heading size="xl" class="mt-2 text-red-600">{{ $overdueInvoices }}</flux:heading>
            </div>
        </div>

        {{-- Recent Invoices --}}
        <div class="rounded-lg border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex items-center justify-between border-b border-zinc-200 px-6 py-4 dark:border-zinc-700">
                <flux:heading size="lg">{{ __('Recent Invoices') }}</flux:heading>
                <flux:button size="sm" variant="ghost" :href="route('invoices.index')" wire:navigate>{{ __('View All') }}</flux:button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                    <thead class="bg-zinc-50 dark:bg-zinc-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase text-zinc-500">{{ __('Invoice #') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase text-zinc-500">{{ __('Customer') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase text-zinc-500">{{ __('Date') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase text-zinc-500">{{ __('Total') }}</th>
                            <th class="px-6 py-3 text-center text-xs font-medium uppercase text-zinc-500">{{ __('Status') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                        @forelse ($recentInvoices as $invoice)
                            <tr wire:key="recent-{{ $invoice->id }}" class="cursor-pointer hover:bg-zinc-50 dark:hover:bg-zinc-800" onclick="window.location='{{ route('invoices.show', $invoice) }}'">
                                <td class="px-6 py-4 text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $invoice->invoice_number }}</td>
                                <td class="px-6 py-4 text-sm text-zinc-500 dark:text-zinc-400">{{ $invoice->customer?->name ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-zinc-500 dark:text-zinc-400">{{ $invoice->invoice_date->format('d M Y') }}</td>
                                <td class="px-6 py-4 text-right text-sm text-zinc-900 dark:text-zinc-100">{{ $invoice->currency }} {{ number_format($invoice->grand_total, 2) }}</td>
                                <td class="px-6 py-4 text-center text-sm">
                                    @php
                                        $colors = ['draft' => 'zinc', 'sent' => 'blue', 'paid' => 'green', 'overdue' => 'red'];
                                    @endphp
                                    <flux:badge :color="$colors[$invoice->status] ?? 'zinc'" size="sm">{{ ucfirst($invoice->status) }}</flux:badge>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-sm text-zinc-500 dark:text-zinc-400">
                                    {{ __('No invoices yet.') }}
                                    <flux:button variant="ghost" size="sm" :href="route('invoices.create')" wire:navigate class="ml-2">{{ __('Create your first invoice') }}</flux:button>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
</div>
