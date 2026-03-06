<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    /** @use HasFactory<\Database\Factories\InvoiceFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'customer_id',
        'payment_method_id',
        'invoice_number',
        'invoice_date',
        'due_date',
        'reference',
        'currency',
        'subtotal',
        'discount',
        'tax_percentage',
        'tax_amount',
        'additional_fee',
        'grand_total',
        'status',
        'notes',
        'terms',
    ];

    protected function casts(): array
    {
        return [
            'invoice_date' => 'date',
            'due_date' => 'date',
            'subtotal' => 'decimal:2',
            'discount' => 'decimal:2',
            'tax_percentage' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'additional_fee' => 'decimal:2',
            'grand_total' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public static function generateInvoiceNumber(): string
    {
        $year = now()->year;
        $prefix = 'INV';

        $lastInvoice = static::query()
            ->where('invoice_number', 'like', "{$prefix}-{$year}-%")
            ->orderByDesc('invoice_number')
            ->first();

        if ($lastInvoice) {
            $lastSequence = (int) substr($lastInvoice->invoice_number, -4);
            $nextSequence = $lastSequence + 1;
        } else {
            $nextSequence = 1;
        }

        return sprintf('%s-%d-%04d', $prefix, $year, $nextSequence);
    }

    public function recalculate(): void
    {
        $this->subtotal = $this->items()->sum('subtotal');
        $this->tax_amount = $this->subtotal * ($this->tax_percentage / 100);
        $this->grand_total = $this->subtotal - $this->discount + $this->tax_amount + $this->additional_fee;
        $this->saveQuietly();
    }
}
