<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $invoice->invoice_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; color: #333; line-height: 1.5; }
        .container { padding: 40px; }
        .header { display: flex; justify-content: space-between; margin-bottom: 30px; }
        .header::after { content: ''; display: table; clear: both; }
        .company-info { float: left; width: 50%; }
        .invoice-info { float: right; width: 50%; text-align: right; }
        .company-name { font-size: 20px; font-weight: bold; margin-bottom: 5px; }
        .invoice-title { font-size: 28px; font-weight: bold; color: #1a1a1a; margin-bottom: 10px; }
        .separator { border-top: 2px solid #e5e7eb; margin: 20px 0; }
        .bill-to { margin-bottom: 20px; }
        .bill-to-label { font-size: 10px; text-transform: uppercase; color: #9ca3af; font-weight: bold; margin-bottom: 5px; }
        .customer-name { font-size: 16px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        thead th { background: #f9fafb; padding: 10px 12px; text-align: left; font-size: 10px; text-transform: uppercase; color: #6b7280; border-bottom: 2px solid #e5e7eb; }
        thead th.right { text-align: right; }
        tbody td { padding: 10px 12px; border-bottom: 1px solid #f3f4f6; }
        tbody td.right { text-align: right; }
        .totals { float: right; width: 300px; }
        .totals::after { content: ''; display: table; clear: both; }
        .total-row { display: flex; justify-content: space-between; padding: 5px 0; }
        .total-row::after { content: ''; display: table; clear: both; }
        .total-label { float: left; }
        .total-value { float: right; }
        .grand-total { font-size: 16px; font-weight: bold; border-top: 2px solid #333; padding-top: 10px; margin-top: 5px; }
        .payment-info { background: #f9fafb; padding: 15px; border-radius: 5px; margin-top: 30px; clear: both; }
        .section-label { font-size: 10px; text-transform: uppercase; color: #9ca3af; font-weight: bold; margin-bottom: 5px; }
        .notes-section { margin-top: 20px; }
        .notes-grid { display: table; width: 100%; }
        .notes-cell { display: table-cell; width: 50%; vertical-align: top; padding-right: 15px; }
        .signature { float: right; text-align: center; margin-top: 40px; }
        .signature img { max-height: 60px; }
        .logo img { max-height: 60px; margin-bottom: 10px; }
        .status { display: inline-block; padding: 3px 10px; border-radius: 3px; font-size: 11px; font-weight: bold; text-transform: uppercase; }
        .status-draft { background: #f3f4f6; color: #6b7280; }
        .status-sent { background: #dbeafe; color: #2563eb; }
        .status-paid { background: #dcfce7; color: #16a34a; }
        .status-overdue { background: #fee2e2; color: #dc2626; }
    </style>
</head>
<body>
    <div class="container">
        {{-- Header --}}
        <div class="header">
            <div class="company-info">
                @if ($company?->logo)
                    <div class="logo">
                        <img src="{{ storage_path('app/public/' . $company->logo) }}" alt="Logo" />
                    </div>
                @endif
                @if ($company)
                    <div class="company-name">{{ $company->company_name }}</div>
                    <div>{!! nl2br(e($company->address)) !!}</div>
                    <div>{{ $company->phone }}</div>
                    <div>{{ $company->email }}</div>
                    @if ($company->tax_number)
                        <div>NPWP: {{ $company->tax_number }}</div>
                    @endif
                @endif
            </div>
            <div class="invoice-info">
                <div class="invoice-title">INVOICE</div>
                <div><strong>{{ $invoice->invoice_number }}</strong></div>
                <div>Date: {{ $invoice->invoice_date->format('d M Y') }}</div>
                @if ($invoice->due_date)
                    <div>Due: {{ $invoice->due_date->format('d M Y') }}</div>
                @endif
                @if ($invoice->reference)
                    <div>Ref: {{ $invoice->reference }}</div>
                @endif
                <div style="margin-top: 5px;">
                    <span class="status status-{{ $invoice->status }}">{{ ucfirst($invoice->status) }}</span>
                </div>
            </div>
        </div>

        <div class="separator"></div>

        {{-- Bill To --}}
        @if ($invoice->customer)
            <div class="bill-to">
                <div class="bill-to-label">Bill To</div>
                <div class="customer-name">{{ $invoice->customer->name }}</div>
                @if ($invoice->customer->company)
                    <div>{{ $invoice->customer->company }}</div>
                @endif
                @if ($invoice->customer->address)
                    <div>{!! nl2br(e($invoice->customer->address)) !!}</div>
                @endif
                @if ($invoice->customer->email)
                    <div>{{ $invoice->customer->email }}</div>
                @endif
                @if ($invoice->customer->phone)
                    <div>{{ $invoice->customer->phone }}</div>
                @endif
            </div>
        @endif

        {{-- Items --}}
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Item</th>
                    <th>Description</th>
                    <th class="right">Qty</th>
                    <th class="right">Unit Price</th>
                    <th class="right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoice->items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $item->item_name }}</strong></td>
                        <td>{{ $item->description }}</td>
                        <td class="right">{{ $item->quantity }}</td>
                        <td class="right">{{ number_format($item->unit_price, 2) }}</td>
                        <td class="right"><strong>{{ number_format($item->subtotal, 2) }}</strong></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Totals --}}
        <div class="totals">
            <div class="total-row">
                <span class="total-label">Subtotal</span>
                <span class="total-value">{{ number_format($invoice->subtotal, 2) }}</span>
            </div>
            @if ($invoice->discount > 0)
                <div class="total-row">
                    <span class="total-label">Discount</span>
                    <span class="total-value" style="color: #dc2626;">- {{ number_format($invoice->discount, 2) }}</span>
                </div>
            @endif
            @if ($invoice->tax_percentage > 0)
                <div class="total-row">
                    <span class="total-label">Tax ({{ $invoice->tax_percentage }}%)</span>
                    <span class="total-value">{{ number_format($invoice->tax_amount, 2) }}</span>
                </div>
            @endif
            @if ($invoice->additional_fee > 0)
                <div class="total-row">
                    <span class="total-label">Additional Fee</span>
                    <span class="total-value">{{ number_format($invoice->additional_fee, 2) }}</span>
                </div>
            @endif
            <div class="total-row grand-total">
                <span class="total-label">Grand Total</span>
                <span class="total-value">{{ $invoice->currency }} {{ number_format($invoice->grand_total, 2) }}</span>
            </div>
        </div>

        {{-- Payment Method --}}
        @if ($invoice->paymentMethod)
            <div class="payment-info">
                <div class="section-label">Payment Method</div>
                <div><strong>{{ $invoice->paymentMethod->bank_name }}</strong></div>
                <div>Account: {{ $invoice->paymentMethod->account_number }}</div>
                <div>Name: {{ $invoice->paymentMethod->account_holder }}</div>
                @if ($invoice->paymentMethod->notes)
                    <div style="margin-top: 5px; font-size: 11px;">{{ $invoice->paymentMethod->notes }}</div>
                @endif
            </div>
        @endif

        {{-- Notes & Terms --}}
        @if ($invoice->notes || $invoice->terms)
            <div class="notes-section">
                <div class="notes-grid">
                    @if ($invoice->notes)
                        <div class="notes-cell">
                            <div class="section-label">Notes</div>
                            <div>{!! nl2br(e($invoice->notes)) !!}</div>
                        </div>
                    @endif
                    @if ($invoice->terms)
                        <div class="notes-cell">
                            <div class="section-label">Terms & Conditions</div>
                            <div>{!! nl2br(e($invoice->terms)) !!}</div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- Signature --}}
        @if ($company?->signature)
            <div class="signature">
                <img src="{{ storage_path('app/public/' . $company->signature) }}" alt="Signature" />
                <div class="separator" style="margin: 5px 0;"></div>
                <div>{{ $company->company_name }}</div>
            </div>
        @endif
    </div>
</body>
</html>
