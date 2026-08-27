<?php

namespace App\Services;

use App\Models\Master\Barang;
use App\Models\Purchase\GoodsReceipt;
use App\Models\Purchase\PurchaseInvoice;
use App\Models\Purchase\PurchaseOrder;
use App\Models\Purchase\PurchasePayment;
use App\Models\Purchase\PurchaseRequest;
use App\Models\Purchase\PurchaseReturn;
use App\Models\Sales\SalesInvoice;
use App\Models\Sales\SalesOrder;
use App\Models\Sales\SalesPayment;
use App\Models\Sales\SalesReturn;
use App\Models\Sales\Shipment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CodeGenerator
{
    /**
     * Generate sequential code with prefix and date
     * Example: PO-202608-0001
     */
    public static function generate(string $table, string $column, string $prefix, int $digits = 4): string
    {
        $dateCode = Carbon::now()->format('Ym'); // e.g. 202608
        $prefixPattern = $prefix . '-' . $dateCode . '-';

        $latest = DB::table($table)
            ->where($column, 'LIKE', $prefixPattern . '%')
            ->orderBy($column, 'desc')
            ->value($column);

        if (!$latest) {
            $nextNumber = 1;
        } else {
            $lastNumStr = substr($latest, strlen($prefixPattern));
            $nextNumber = intval($lastNumStr) + 1;
        }

        $code = $prefixPattern . str_pad((string)$nextNumber, $digits, '0', STR_PAD_LEFT);

        // Safety check to ensure absolute uniqueness
        while (DB::table($table)->where($column, $code)->exists()) {
            $nextNumber++;
            $code = $prefixPattern . str_pad((string)$nextNumber, $digits, '0', STR_PAD_LEFT);
        }

        return $code;
    }

    /**
     * Master Barang SKU Generator
     * Matches seeded BRG001..BRG030 format (e.g. BRG031, BRG032)
     */
    public static function generateSku(): string
    {
        $latestBrg = DB::table('m_barang')
            ->where('sku', 'LIKE', 'BRG%')
            ->orderBy(DB::raw('LENGTH(sku)'), 'desc')
            ->orderBy('sku', 'desc')
            ->value('sku');

        if ($latestBrg) {
            preg_match('/(\d+)/', $latestBrg, $matches);
            $num = isset($matches[1]) ? intval($matches[1]) + 1 : 1;
            $code = 'BRG' . str_pad((string)$num, 3, '0', STR_PAD_LEFT);
            while (DB::table('m_barang')->where('sku', $code)->exists()) {
                $num++;
                $code = 'BRG' . str_pad((string)$num, 3, '0', STR_PAD_LEFT);
            }
            return $code;
        }

        return self::generate('m_barang', 'sku', 'BRG', 4);
    }

    // Purchase Request (e.g. PR-202608-0001)
    public static function generatePurchaseRequestNo(): string
    {
        return self::generate('nota_permintaan_pembelian', 'no_faktur', 'PR', 4);
    }

    // Purchase Order (e.g. PO-202608-0001)
    public static function generatePurchaseOrderNo(): string
    {
        return self::generate('nota_order_pembelian', 'no_order_pembelian', 'PO', 4);
    }

    // Goods Receipt (e.g. GR-202608-0001)
    public static function generateGoodsReceiptNo(): string
    {
        return self::generate('nota_penerimaan_barang', 'no_penerimaan', 'GR', 4);
    }

    // Purchase Invoice (e.g. PINV-202608-0001)
    public static function generatePurchaseInvoiceNo(): string
    {
        return self::generate('nota_tagihan_pembelian', 'no_invoice_beli', 'PINV', 4);
    }

    // Purchase Payment (e.g. PPAY-202608-0001)
    public static function generatePurchasePaymentNo(): string
    {
        return self::generate('nota_pelunasan_pembelian', 'no_pembayaran_beli', 'PPAY', 4);
    }

    // Purchase Return (e.g. PRET-202608-0001)
    public static function generatePurchaseReturnNo(): string
    {
        return self::generate('nota_retur_barang', 'nota_retur', 'PRET', 4);
    }

    // Sales Order (e.g. SO-202608-0001)
    public static function generateSalesOrderNo(): string
    {
        return self::generate('nota_order_penjualan', 'no_order_penjualan', 'SO', 4);
    }

    // Shipment (e.g. SHP-202608-0001)
    public static function generateShipmentNo(): string
    {
        return self::generate('nota_pengiriman_barang', 'no_pengiriman', 'SHP', 4);
    }

    // Sales Invoice (e.g. SINV-202608-0001)
    public static function generateSalesInvoiceNo(): string
    {
        return self::generate('nota_tagihan_penjualan', 'no_invoice_jual', 'SINV', 4);
    }

    // Sales Payment (e.g. SPAY-202608-0001)
    public static function generateSalesPaymentNo(): string
    {
        return self::generate('nota_pelunasan_penjualan', 'no_pembayaran', 'SPAY', 4);
    }

    // Sales Return (e.g. SRET-202608-0001)
    public static function generateSalesReturnNo(): string
    {
        return self::generate('nota_retur_penjualan', 'no_retur_jual', 'SRET', 4);
    }
}
