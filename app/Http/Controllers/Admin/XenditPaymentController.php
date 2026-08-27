<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Sales\SalesInvoice;
use App\Services\XenditService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class XenditPaymentController extends Controller
{
    protected XenditService $xendit;

    public function __construct(XenditService $xendit)
    {
        $this->xendit = $xendit;
    }

    public function index()
    {
        $payments = Payment::latest()->paginate(15);

        return view('admin.xendit.index', ['payments' => $payments]);
    }

    public function create()
    {
        $invoices = SalesInvoice::whereIn('status', [1, 2])->orderByDesc('tanggal')->get(['no_invoice_jual']);

        return view('admin.xendit.create', ['invoices' => $invoices]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_invoice_jual' => 'nullable|string|exists:nota_tagihan_penjualan,no_invoice_jual',
            'amount' => 'required|numeric|min:1',
            'payer_email' => 'nullable|email',
            'description' => 'nullable|string|max:255',
        ]);

        $referenceId = 'PAY-' . strtoupper(Str::random(10));

        $xenditResponse = $this->xendit->createInvoice([
            'external_id' => $referenceId,
            'amount' => $validated['amount'],
            'payer_email' => $validated['payer_email'] ?? null,
            'description' => $validated['description'] ?? ('Pembayaran ' . ($validated['no_invoice_jual'] ?? $referenceId)),
        ]);

        $payment = Payment::create([
            'reference_id' => $referenceId,
            'no_invoice_jual' => $validated['no_invoice_jual'] ?? null,
            'xendit_invoice_id' => $xenditResponse['id'] ?? null,
            'external_id' => $xenditResponse['external_id'] ?? $referenceId,
            'payer_email' => $validated['payer_email'] ?? null,
            'description' => $validated['description'] ?? null,
            'amount' => $validated['amount'],
            'currency' => $xenditResponse['currency'] ?? 'IDR',
            'status' => $xenditResponse['status'] ?? 'PENDING',
            'invoice_url' => $xenditResponse['invoice_url'] ?? null,
            'expiry_date' => $xenditResponse['expiry_date'] ?? null,
            'raw_response' => $xenditResponse,
        ]);

        return redirect()
            ->route('admin.xendit.show', $payment->id)
            ->with('success', 'Invoice pembayaran berhasil dibuat. Silakan bagikan URL invoice ke pelanggan.');
    }

    public function show(Payment $payment)
    {
        return view('admin.xendit.show', ['payment' => $payment]);
    }

    /**
     * Tarik ulang status terbaru langsung dari Xendit (polling manual, tanpa webhook).
     */
    public function refresh(Payment $payment)
    {
        if ($payment->xendit_invoice_id) {
            $xenditResponse = $this->xendit->getInvoice($payment->xendit_invoice_id);
            $this->applyXenditData($payment, $xenditResponse);
        }

        return redirect()->route('admin.xendit.show', $payment->id)->with('success', 'Status pembayaran diperbarui dari Xendit.');
    }

    /**
     * Webhook callback dari Xendit. Route ini HARUS di luar middleware auth & CSRF.
     */
    public function callback(Request $request)
    {
        $token = $request->header('x-callback-token');

        if (!$token || $token !== config('xendit.callback_token')) {
            abort(403, 'Invalid callback token');
        }

        $data = $request->all();

        $payment = Payment::where('xendit_invoice_id', $data['id'] ?? null)
            ->orWhere('external_id', $data['external_id'] ?? null)
            ->first();

        if ($payment) {
            $this->applyXenditData($payment, $data);
        }

        return response()->json(['message' => 'ok']);
    }


    public function pollStatus(Payment $payment)
{
    if (!$payment->xendit_invoice_id) {
        return response()->json([
            'success' => false,
            'message' => 'Invoice Xendit tidak ditemukan.'
        ], 404);
    }

    $invoice = $this->xendit->getInvoice($payment->xendit_invoice_id);

    $payment->update([
        'status' => $invoice['status'] ?? $payment->status,
        'payment_method' => $invoice['payment_method'] ?? $payment->payment_method,
        'payment_channel' => $invoice['payment_channel'] ?? $payment->payment_channel,
        'paid_amount' => $invoice['paid_amount'] ?? $payment->paid_amount,
        'paid_at' => !empty($invoice['paid_at'])
            ? $invoice['paid_at']
            : $payment->paid_at,
        'raw_response' => $invoice,
    ]);

    return response()->json([
        'success' => true,
        'status' => $payment->status,
        'paid_at' => optional($payment->paid_at)->format('d-m-Y H:i'),
        'payment_method' => $payment->payment_method,
        'payment_channel' => $payment->payment_channel,
    ]);
}

    protected function applyXenditData(Payment $payment, array $data): void
    {
        $payment->update([
            'status' => $data['status'] ?? $payment->status,
            'paid_at' => $data['paid_at'] ?? $payment->paid_at,
            'paid_amount' => $data['paid_amount'] ?? $payment->paid_amount,
            'payment_method' => $data['payment_method'] ?? $payment->payment_method,
            'payment_channel' => $data['payment_channel'] ?? $payment->payment_channel,
            'raw_response' => $data,
        ]);

        if ($payment->no_invoice_jual) {
            $inv = SalesInvoice::find($payment->no_invoice_jual);
            if ($inv) $inv->updatePaymentStatus();
        }
    }
}
