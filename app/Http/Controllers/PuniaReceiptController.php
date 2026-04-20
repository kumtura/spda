<?php

namespace App\Http\Controllers;

use App\Models\Danapunia;
use App\Models\PuniaPendatang;
use App\Models\Usaha;
use Barryvdh\DomPDF\Facade\Pdf;

class PuniaReceiptController extends Controller
{
    public function show(string $code)
    {
        [$payment, $usaha, $receiptCode, $bulanName, $village] = $this->resolveReceiptData($code);

        return view('front.pages.punia_receipt', compact(
            'payment',
            'usaha',
            'receiptCode',
            'bulanName',
            'village'
        ));
    }

    public function download(string $code)
    {
        [$payment, $usaha, $receiptCode, $bulanName, $village] = $this->resolveReceiptData($code);

        $pdf = Pdf::loadView('pdf.receipt_punia', compact(
            'payment',
            'usaha',
            'receiptCode',
            'bulanName',
            'village'
        ));

        return $pdf->download('receipt-' . strtolower($receiptCode) . '.pdf');
    }

    public function showPendatang(string $code)
    {
        [$payment, $pendatang, $receiptCode, $bulanName, $village] = $this->resolvePendatangReceiptData($code);

        return view('front.pages.punia_pendatang_receipt', compact(
            'payment',
            'pendatang',
            'receiptCode',
            'bulanName',
            'village'
        ));
    }

    public function downloadPendatang(string $code)
    {
        [$payment, $pendatang, $receiptCode, $bulanName, $village] = $this->resolvePendatangReceiptData($code);

        $pdf = Pdf::loadView('pdf.receipt_punia_pendatang', compact(
            'payment',
            'pendatang',
            'receiptCode',
            'bulanName',
            'village'
        ));

        return $pdf->download('receipt-' . strtolower($receiptCode) . '.pdf');
    }

    protected function resolveReceiptData(string $code): array
    {
        $numericId = $this->extractReceiptId($code);
        abort_if(!$numericId, 404);

        $payment = Danapunia::findOrFail($numericId);
        $usaha = Usaha::get_detailUsaha($payment->id_usaha);
        $receiptCode = $this->formatReceiptCode($payment->id_dana_punia);
        $bulanName = $this->resolveMonthName((int) ($payment->bulan_punia ?: $payment->bulan));
        $village = $this->getVillageData();

        return [$payment, $usaha, $receiptCode, $bulanName, $village];
    }

    protected function resolvePendatangReceiptData(string $code): array
    {
        $numericId = $this->extractPendatangReceiptId($code);
        abort_if(!$numericId, 404);

        $payment = PuniaPendatang::with('pendatang.banjar')->findOrFail($numericId);
        $pendatang = $payment->pendatang;
        $receiptCode = $this->formatPendatangReceiptCode($payment->id_punia_pendatang);
        $bulanName = $this->resolvePendatangMonthName((string) $payment->bulan_tahun);
        $village = $this->getVillageData();

        return [$payment, $pendatang, $receiptCode, $bulanName, $village];
    }

    protected function extractReceiptId(string $code): ?int
    {
        if (!preg_match('/^PN-(\d+)$/i', trim($code), $matches)) {
            return null;
        }

        return (int) $matches[1];
    }

    protected function formatReceiptCode(int $id): string
    {
        return 'PN-' . str_pad((string) $id, 6, '0', STR_PAD_LEFT);
    }

    protected function extractPendatangReceiptId(string $code): ?int
    {
        if (!preg_match('/^TM-(\d+)$/i', trim($code), $matches)) {
            return null;
        }

        return (int) $matches[1];
    }

    protected function formatPendatangReceiptCode(int $id): string
    {
        return 'TM-' . str_pad((string) $id, 6, '0', STR_PAD_LEFT);
    }

    protected function resolveMonthName(int $month): string
    {
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        return $months[$month] ?? '-';
    }

    protected function resolvePendatangMonthName(string $bulanTahun): string
    {
        if (preg_match('/^(\d{4})-(\d{2})$/', $bulanTahun, $matches)) {
            return $this->resolveMonthName((int) $matches[2]) . ' ' . $matches[1];
        }

        if (preg_match('/^(\d{2})\/(\d{4})$/', $bulanTahun, $matches)) {
            return $this->resolveMonthName((int) $matches[1]) . ' ' . $matches[2];
        }

        return $bulanTahun ?: '-';
    }

    protected function getVillageData(): array
    {
        $settingsPath = storage_path('app/settings.json');
        if (file_exists($settingsPath)) {
            return json_decode(file_get_contents($settingsPath), true) ?: ['name' => 'SPDA'];
        }

        return ['name' => 'SPDA'];
    }
}