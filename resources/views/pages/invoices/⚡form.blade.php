<?php

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Invoice')] class extends Component {
    public ?Invoice $invoice = null;

    public string $invoice_number = '';
    public string $invoice_date = '';
    public string $due_date = '';
    public string $reference = '';
    public string $currency = 'IDR';
    public ?int $customer_id = null;
    public ?int $payment_method_id = null;
    public float $discount = 0;
    public float $tax_percentage = 0;
    public float $additional_fee = 0;
    public string $notes = '';
    public string $terms = '';

    /** @var array<int, array{item_name: string, description: string, quantity: int, unit_price: float, subtotal: float}> */
    public array $items = [];

    public function mount(?Invoice $invoice = null): void
    {
        if ($invoice?->exists) {
            abort_unless($invoice->user_id === Auth::id() || Auth::user()->isSuperAdmin(), 403);
            $this->invoice = $invoice;
            $this->invoice_number = $invoice->invoice_number;
            $this->invoice_date = $invoice->invoice_date->format('Y-m-d');
            $this->due_date = $invoice->due_date?->format('Y-m-d') ?? '';
            $this->reference = $invoice->reference ?? '';
            $this->currency = $invoice->currency;
            $this->customer_id = $invoice->customer_id;
            $this->payment_method_id = $invoice->payment_method_id;
            $this->discount = (float) $invoice->discount;
            $this->tax_percentage = (float) $invoice->tax_percentage;
            $this->additional_fee = (float) $invoice->additional_fee;
            $this->notes = $invoice->notes ?? '';
            $this->terms = $invoice->terms ?? '';
            $this->items = $invoice->items->map(fn ($item) => [
                'item_name' => $item->item_name,
                'description' => $item->description ?? '',
                'quantity' => $item->quantity,
                'unit_price' => (float) $item->unit_price,
                'subtotal' => (float) $item->subtotal,
            ])->toArray();
        } else {
            $this->invoice_number = Invoice::generateInvoiceNumber();
            $this->invoice_date = now()->format('Y-m-d');
            $this->due_date = now()->addDays(30)->format('Y-m-d');
            $this->addItem();
        }
    }

    public function addItem(): void
    {
        $this->items[] = [
            'item_name' => '',
            'description' => '',
            'quantity' => 1,
            'unit_price' => 0,
            'subtotal' => 0,
        ];
    }

    public function removeItem(int $index): void
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function updatedItems(): void
    {
        foreach ($this->items as $index => $item) {
            $this->items[$index]['subtotal'] = (float) ($item['quantity'] ?? 0) * (float) ($item['unit_price'] ?? 0);
        }
    }

    #[Computed]
    public function subtotal(): float
    {
        return collect($this->items)->sum('subtotal');
    }

    #[Computed]
    public function taxAmount(): float
    {
        return $this->subtotal * ($this->tax_percentage / 100);
    }

    #[Computed]
    public function grandTotal(): float
    {
        return $this->subtotal - $this->discount + $this->taxAmount + $this->additional_fee;
    }

    public function save(): void
    {
        $validated = $this->validate([
            'invoice_number' => ['required', 'string', 'max:50'],
            'invoice_date' => ['required', 'date'],
            'due_date' => ['nullable', 'date'],
            'reference' => ['nullable', 'string', 'max:255'],
            'currency' => ['required', 'string', 'max:10'],
            'customer_id' => ['nullable', 'exists:customers,id'],
            'payment_method_id' => ['nullable', 'exists:payment_methods,id'],
            'discount' => ['numeric', 'min:0'],
            'tax_percentage' => ['numeric', 'min:0', 'max:100'],
            'additional_fee' => ['numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'terms' => ['nullable', 'string', 'max:2000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.item_name' => ['required', 'string', 'max:255'],
            'items.*.description' => ['nullable', 'string', 'max:500'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
        ]);

        $invoiceData = collect($validated)->except('items')->merge([
            'subtotal' => $this->subtotal,
            'tax_amount' => $this->taxAmount,
            'grand_total' => $this->grandTotal,
        ])->toArray();

        if ($this->invoice?->exists) {
            $this->invoice->update($invoiceData);
            $this->invoice->items()->delete();
            $invoice = $this->invoice;
        } else {
            $invoice = Auth::user()->invoices()->create($invoiceData);
        }

        foreach ($this->items as $item) {
            $invoice->items()->create([
                'item_name' => $item['item_name'],
                'description' => $item['description'] ?? '',
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'subtotal' => (float) $item['quantity'] * (float) $item['unit_price'],
            ]);
        }

        $this->redirect(route('invoices.show', $invoice), navigate: true);
    }

    public function with(): array
    {
        $user = Auth::user();

        return [
            'customers' => $user->isSuperAdmin()
                ? Customer::query()->orderBy('name')->get()
                : $user->customers()->orderBy('name')->get(),
            'paymentMethods' => $user->isSuperAdmin()
                ? PaymentMethod::all()
                : $user->paymentMethods()->get(),
        ];
    }
}; ?>

    <div class="space-y-6">
        <flux:heading size="xl">
            {{ $invoice?->exists ? __('Edit Invoice') : __('New Invoice') }}
        </flux:heading>

        <form wire:submit="save" class="space-y-8">
            {{-- Invoice Header --}}
            <div class="rounded-lg border border-zinc-200 p-6 dark:border-zinc-700">
                <flux:heading size="lg" class="mb-4">{{ __('Invoice Details') }}</flux:heading>
                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                    <flux:input wire:model="invoice_number" :label="__('Invoice Number')" required readonly />
                    <flux:input wire:model="invoice_date" :label="__('Invoice Date')" type="date" required />
                    <flux:input wire:model="due_date" :label="__('Due Date')" type="date" />
                    <flux:input wire:model="reference" :label="__('Reference')" />
                </div>
                <div class="mt-4 grid gap-4 md:grid-cols-3">
                    <flux:field>
                        <flux:label>{{ __('Customer') }}</flux:label>
                        <div x-data="{
                            open: false,
                            search: '',
                            selected: @js($customer_id),
                            customers: @js($customers->map(fn ($c) => ['id' => $c->id, 'label' => $c->name . ($c->company ? ' (' . $c->company . ')' : '')])->values()),
                            get filtered() {
                                if (!this.search) return this.customers;
                                return this.customers.filter(c => c.label.toLowerCase().includes(this.search.toLowerCase()));
                            },
                            get selectedLabel() {
                                const c = this.customers.find(c => c.id === this.selected);
                                return c ? c.label : '';
                            },
                            select(id) {
                                this.selected = id;
                                this.open = false;
                                this.search = '';
                                $wire.set('customer_id', id);
                            },
                            clear() {
                                this.selected = null;
                                this.search = '';
                                $wire.set('customer_id', null);
                            }
                        }" x-on:click.outside="open = false" class="relative">
                            <div class="relative">
                                <input
                                    type="text"
                                    x-show="open"
                                    x-ref="searchInput"
                                    x-model="search"
                                    x-on:keydown.escape="open = false"
                                    placeholder="{{ __('Search customer...') }}"
                                    class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-zinc-400 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100"
                                />
                                <button
                                    type="button"
                                    x-show="!open"
                                    x-on:click="open = true; $nextTick(() => $refs.searchInput.focus())"
                                    class="flex w-full items-center justify-between rounded-lg border border-zinc-200 bg-white px-3 py-2 text-left text-sm shadow-sm hover:border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100"
                                >
                                    <span x-text="selectedLabel || '{{ __('Select Customer') }}'" :class="!selected && 'text-zinc-400 dark:text-zinc-500'"></span>
                                    <div class="flex items-center gap-1">
                                        <svg x-show="selected" x-on:click.stop="clear()" class="h-4 w-4 text-zinc-400 hover:text-zinc-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                        <svg class="h-4 w-4 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                    </div>
                                </button>
                            </div>
                            <div x-show="open" x-transition class="absolute z-50 mt-1 max-h-60 w-full overflow-auto rounded-lg border border-zinc-200 bg-white py-1 shadow-lg dark:border-zinc-600 dark:bg-zinc-800">
                                <template x-for="c in filtered" :key="c.id">
                                    <button
                                        type="button"
                                        x-on:click="select(c.id)"
                                        x-text="c.label"
                                        :class="selected === c.id ? 'bg-zinc-100 dark:bg-zinc-700' : ''"
                                        class="block w-full px-3 py-2 text-left text-sm hover:bg-zinc-100 dark:text-zinc-100 dark:hover:bg-zinc-700"
                                    ></button>
                                </template>
                                <div x-show="filtered.length === 0" class="px-3 py-2 text-sm text-zinc-400">{{ __('No customers found.') }}</div>
                            </div>
                        </div>
                        <flux:error name="customer_id" />
                    </flux:field>
                    <flux:select wire:model="payment_method_id" :label="__('Payment Method')">
                        <option value="">{{ __('Select Payment Method') }}</option>
                        @foreach ($paymentMethods as $method)
                            <option value="{{ $method->id }}">{{ $method->bank_name }} - {{ $method->account_number }}</option>
                        @endforeach
                    </flux:select>
                    <flux:select wire:model="currency" :label="__('Currency')">
                        <option value="IDR">IDR</option>
                        <option value="USD">USD</option>
                        <option value="SGD">SGD</option>
                    </flux:select>
                </div>
            </div>

            {{-- Invoice Items --}}
            <div class="rounded-lg border border-zinc-200 p-6 dark:border-zinc-700">
                <div class="mb-4 flex items-center justify-between">
                    <flux:heading size="lg">{{ __('Items') }}</flux:heading>
                    <flux:button size="sm" variant="ghost" icon="plus" wire:click.prevent="addItem">{{ __('Add Item') }}</flux:button>
                </div>

                <div class="space-y-4">
                    @foreach ($items as $index => $item)
                        <div wire:key="item-{{ $index }}" class="rounded-lg border border-zinc-100 bg-zinc-50 p-4 dark:border-zinc-700 dark:bg-zinc-800">
                            <div class="grid gap-4 md:grid-cols-12">
                                <div class="md:col-span-4">
                                    <flux:input wire:model="items.{{ $index }}.item_name" :label="__('Item Name')" required size="sm" />
                                </div>
                                <div class="md:col-span-3">
                                    <flux:textarea wire:model="items.{{ $index }}.description" :label="__('Description')" size="sm" rows="2" />
                                </div>
                                <div class="md:col-span-1">
                                    <flux:input wire:model.live="items.{{ $index }}.quantity" :label="__('Qty')" type="number" min="1" required size="sm" />
                                </div>
                                <div class="md:col-span-2">
                                    <flux:input wire:model.live="items.{{ $index }}.unit_price" :label="__('Unit Price')" type="number" step="0.01" min="0" required size="sm" />
                                </div>
                                <div class="md:col-span-1 flex items-end">
                                    <flux:text class="pb-2 text-sm font-medium">{{ number_format($items[$index]['subtotal'] ?? 0, 2) }}</flux:text>
                                </div>
                                <div class="md:col-span-1 flex items-end">
                                    @if (count($items) > 1)
                                        <flux:button size="sm" variant="ghost" icon="trash" wire:click.prevent="removeItem({{ $index }})" class="mb-1" />
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Payment Summary --}}
            <div class="rounded-lg border border-zinc-200 p-6 dark:border-zinc-700">
                <flux:heading size="lg" class="mb-4">{{ __('Payment Summary') }}</flux:heading>
                <div class="grid gap-4 md:grid-cols-2">
                    <div class="space-y-4">
                        <flux:input wire:model.live="discount" :label="__('Discount')" type="number" step="0.01" min="0" />
                        <flux:input wire:model.live="tax_percentage" :label="__('Tax (%)')" type="number" step="0.01" min="0" max="100" />
                        <flux:input wire:model.live="additional_fee" :label="__('Additional Fee')" type="number" step="0.01" min="0" />
                    </div>
                    <div class="rounded-lg bg-zinc-50 p-4 dark:bg-zinc-800">
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <flux:text>{{ __('Subtotal') }}</flux:text>
                                <flux:text class="font-medium">{{ number_format($this->subtotal, 2) }}</flux:text>
                            </div>
                            <div class="flex justify-between">
                                <flux:text>{{ __('Discount') }}</flux:text>
                                <flux:text class="text-red-500">- {{ number_format($discount, 2) }}</flux:text>
                            </div>
                            <div class="flex justify-between">
                                <flux:text>{{ __('Tax') }} ({{ $tax_percentage }}%)</flux:text>
                                <flux:text>+ {{ number_format($this->taxAmount, 2) }}</flux:text>
                            </div>
                            <div class="flex justify-between">
                                <flux:text>{{ __('Additional Fee') }}</flux:text>
                                <flux:text>+ {{ number_format($additional_fee, 2) }}</flux:text>
                            </div>
                            <flux:separator />
                            <div class="flex justify-between">
                                <flux:heading size="lg">{{ __('Grand Total') }}</flux:heading>
                                <flux:heading size="lg">{{ $currency }} {{ number_format($this->grandTotal, 2) }}</flux:heading>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Notes & Terms --}}
            <div class="rounded-lg border border-zinc-200 p-6 dark:border-zinc-700">
                <flux:heading size="lg" class="mb-4">{{ __('Notes & Terms') }}</flux:heading>
                <div class="grid gap-4 md:grid-cols-2">
                    <flux:textarea wire:model="notes" :label="__('Notes')" rows="4" />
                    <flux:textarea wire:model="terms" :label="__('Terms & Conditions')" rows="4" />
                </div>
            </div>

            <div class="flex items-center gap-4">
                <flux:button variant="primary" type="submit">{{ __('Save Invoice') }}</flux:button>
                <flux:button variant="ghost" :href="route('invoices.index')" wire:navigate>{{ __('Cancel') }}</flux:button>
            </div>
        </form>
    </div>
