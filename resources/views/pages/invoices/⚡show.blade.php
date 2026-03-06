<?php

use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Invoice Preview')] class extends Component {
    public Invoice $invoice;

    public function mount(Invoice $invoice): void
    {
        abort_unless($invoice->user_id === Auth::id() || Auth::user()->isSuperAdmin(), 403);
        $this->invoice = $invoice->load(['customer', 'items', 'paymentMethod']);
    }

    public function with(): array
    {
        $company = Auth::user()->isSuperAdmin()
            ? $this->invoice->user->company
            : Auth::user()->company;

        return [
            'company' => $company,
        ];
    }
}; ?>

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <flux:heading size="xl">{{ __('Invoice') }} {{ $invoice->invoice_number }}</flux:heading>
            <div class="flex gap-2">
                <flux:button variant="ghost" icon="pencil-square" :href="route('invoices.edit', $invoice)" wire:navigate>
                    {{ __('Edit') }}
                </flux:button>
                <flux:button variant="primary" icon="arrow-down-tray" :href="route('invoices.pdf', $invoice)">
                    {{ __('Download PDF') }}
                </flux:button>
            </div>
        </div>

        {{-- Invoice Preview --}}
        <div class="rounded-lg border border-zinc-200 bg-white p-8 dark:border-zinc-700 dark:bg-zinc-900" id="invoice-preview">
            {{-- Header --}}
            <div class="flex items-start justify-between">
                <div>
                    @if ($company?->logo)
                        <img src="{{ Storage::url($company->logo) }}" alt="Logo" class="mb-4 h-16" />
                    @endif
                    @if ($company)
                        <flux:heading size="lg">{{ $company->company_name }}</flux:heading>
                        <flux:text class="whitespace-pre-line">{{ $company->address }}</flux:text>
                        <flux:text>{{ $company->phone }}</flux:text>
                        <flux:text>{{ $company->email }}</flux:text>
                        @if ($company->tax_number)
                            <flux:text>NPWP: {{ $company->tax_number }}</flux:text>
                        @endif
                    @endif
                </div>
                <div class="text-right">
                    <flux:heading size="xl">INVOICE</flux:heading>
                    <flux:text class="mt-2">{{ $invoice->invoice_number }}</flux:text>
                    <flux:text>{{ __('Date') }}: {{ $invoice->invoice_date->format('d M Y') }}</flux:text>
                    @if ($invoice->due_date)
                        <flux:text>{{ __('Due') }}: {{ $invoice->due_date->format('d M Y') }}</flux:text>
                    @endif
                    @if ($invoice->reference)
                        <flux:text>{{ __('Ref') }}: {{ $invoice->reference }}</flux:text>
                    @endif
                    <div class="mt-2">
                        @php
                            $statusColors = ['draft' => 'zinc', 'sent' => 'blue', 'paid' => 'green', 'overdue' => 'red'];
                        @endphp
                        <flux:badge :color="$statusColors[$invoice->status] ?? 'zinc'">{{ ucfirst($invoice->status) }}</flux:badge>
                    </div>
                </div>
            </div>

            <flux:separator class="my-6" />

            {{-- Bill To --}}
            @if ($invoice->customer)
                <div class="mb-6">
                    <flux:text class="mb-1 font-semibold uppercase text-zinc-400">{{ __('Bill To') }}</flux:text>
                    <flux:heading>{{ $invoice->customer->name }}</flux:heading>
                    @if ($invoice->customer->company)
                        <flux:text>{{ $invoice->customer->company }}</flux:text>
                    @endif
                    <flux:text class="whitespace-pre-line">{{ $invoice->customer->address }}</flux:text>
                    <flux:text>{{ $invoice->customer->email }}</flux:text>
                    <flux:text>{{ $invoice->customer->phone }}</flux:text>
                </div>
            @endif

            {{-- Items Table --}}
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">#</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">{{ __('Item') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">{{ __('Description') }}</th>
                            <th class="px-4 py-3 text-right text-xs font-medium uppercase text-zinc-500">{{ __('Qty') }}</th>
                            <th class="px-4 py-3 text-right text-xs font-medium uppercase text-zinc-500">{{ __('Unit Price') }}</th>
                            <th class="px-4 py-3 text-right text-xs font-medium uppercase text-zinc-500">{{ __('Subtotal') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                        @foreach ($invoice->items as $index => $item)
                            <tr>
                                <td class="px-4 py-3 text-sm">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 text-sm font-medium">{{ $item->item_name }}</td>
                                <td class="px-4 py-3 text-sm text-zinc-500">{{ $item->description }}</td>
                                <td class="px-4 py-3 text-right text-sm">{{ $item->quantity }}</td>
                                <td class="px-4 py-3 text-right text-sm">{{ number_format($item->unit_price, 2) }}</td>
                                <td class="px-4 py-3 text-right text-sm font-medium">{{ number_format($item->subtotal, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Totals --}}
            <div class="mt-6 flex justify-end">
                <div class="w-full max-w-xs space-y-2">
                    <div class="flex justify-between">
                        <flux:text>{{ __('Subtotal') }}</flux:text>
                        <flux:text>{{ number_format($invoice->subtotal, 2) }}</flux:text>
                    </div>
                    @if ($invoice->discount > 0)
                        <div class="flex justify-between">
                            <flux:text>{{ __('Discount') }}</flux:text>
                            <flux:text class="text-red-500">- {{ number_format($invoice->discount, 2) }}</flux:text>
                        </div>
                    @endif
                    @if ($invoice->tax_percentage > 0)
                        <div class="flex justify-between">
                            <flux:text>{{ __('Tax') }} ({{ $invoice->tax_percentage }}%)</flux:text>
                            <flux:text>{{ number_format($invoice->tax_amount, 2) }}</flux:text>
                        </div>
                    @endif
                    @if ($invoice->additional_fee > 0)
                        <div class="flex justify-between">
                            <flux:text>{{ __('Additional Fee') }}</flux:text>
                            <flux:text>{{ number_format($invoice->additional_fee, 2) }}</flux:text>
                        </div>
                    @endif
                    <flux:separator />
                    <div class="flex justify-between">
                        <flux:heading size="lg">{{ __('Grand Total') }}</flux:heading>
                        <flux:heading size="lg">{{ $invoice->currency }} {{ number_format($invoice->grand_total, 2) }}</flux:heading>
                    </div>
                </div>
            </div>

            {{-- Payment Method --}}
            @if ($invoice->paymentMethod)
                <div class="mt-8 rounded-lg bg-zinc-50 p-4 dark:bg-zinc-800">
                    <flux:text class="mb-1 font-semibold uppercase text-zinc-400">{{ __('Payment Method') }}</flux:text>
                    <flux:text>{{ $invoice->paymentMethod->bank_name }}</flux:text>
                    <flux:text>{{ __('Account') }}: {{ $invoice->paymentMethod->account_number }}</flux:text>
                    <flux:text>{{ __('Name') }}: {{ $invoice->paymentMethod->account_holder }}</flux:text>
                    @if ($invoice->paymentMethod->notes)
                        <flux:text class="mt-1 text-xs">{{ $invoice->paymentMethod->notes }}</flux:text>
                    @endif
                </div>
            @endif

            {{-- Notes & Terms --}}
            @if ($invoice->notes || $invoice->terms)
                <div class="mt-8 grid gap-4 md:grid-cols-2">
                    @if ($invoice->notes)
                        <div>
                            <flux:text class="mb-1 font-semibold uppercase text-zinc-400">{{ __('Notes') }}</flux:text>
                            <flux:text class="whitespace-pre-line">{{ $invoice->notes }}</flux:text>
                        </div>
                    @endif
                    @if ($invoice->terms)
                        <div>
                            <flux:text class="mb-1 font-semibold uppercase text-zinc-400">{{ __('Terms & Conditions') }}</flux:text>
                            <flux:text class="whitespace-pre-line">{{ $invoice->terms }}</flux:text>
                        </div>
                    @endif
                </div>
            @endif

            {{-- Signature --}}
            @if ($company?->signature)
                <div class="mt-8 flex justify-end">
                    <div class="text-center">
                        <img src="{{ Storage::url($company->signature) }}" alt="Signature" class="h-20" />
                        <flux:separator class="my-2" />
                        <flux:text>{{ $company->company_name }}</flux:text>
                    </div>
                </div>
            @endif
        </div>
    </div>
