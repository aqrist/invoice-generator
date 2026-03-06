<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class InvoicePdfController extends Controller
{
    public function __invoke(Invoice $invoice): Response
    {
        abort_unless($invoice->user_id === Auth::id() || Auth::user()->isSuperAdmin(), 403);

        $invoice->load(['customer', 'items', 'paymentMethod']);
        $company = Auth::user()->isSuperAdmin()
            ? $invoice->user->company
            : Auth::user()->company;

        $pdf = Pdf::loadView('pdf.invoice', compact('invoice', 'company'));

        return $pdf->download("{$invoice->invoice_number}.pdf");
    }
}
