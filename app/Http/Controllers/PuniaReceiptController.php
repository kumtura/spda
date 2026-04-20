<?php

namespace App\Http\Controllers;

use App\Models\Danapunia;
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

    protected function resolveMonthName(int $month): string
    {
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        return $months[$month] ?? '-';
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