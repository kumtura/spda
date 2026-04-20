<?php

namespace App\Services;

use App\Models\Danapunia;
use App\Models\PuniaPendatang;
use App\Models\PuniaPura;
use App\Models\Sumbangan;
use App\Models\Usaha;

class PaymentOrderService
{
    public function resolveRecord(string $type, string $orderId)
    {
        $id = $this->extractNumericId($type, $orderId);

        if (!$id) {
            return null;
        }

        return match ($type) {
            'punia_pendatang' => PuniaPendatang::with('pendatang')->find($id),
            'punia_pura' => PuniaPura::find($id),
            'punia' => Danapunia::find($id),
            'donasi' => Sumbangan::find($id),
            default => null,
        };
    }

    public function extractNumericId(string $type, string $orderId): ?string
    {
        $segments = explode('-', $orderId);

        return match ($type) {
            'punia_pura' => $segments[2] ?? null,
            'punia', 'donasi', 'punia_pendatang' => $segments[1] ?? null,
            default => null,
        };
    }

    public function decodePaymentData($record): array
    {
        if (!$record || empty($record->payment_data)) {
            return [];
        }

        if (is_array($record->payment_data)) {
            return $record->payment_data;
        }

        return json_decode($record->payment_data, true) ?: [];
    }

    public function extractMethodCode($record, ?array $paymentData = null): ?string
    {
        $paymentData = $paymentData ?? $this->decodePaymentData($record);
        $storedMethod = $record->metode ?? $record->metode_pembayaran ?? null;

        if ($storedMethod && !in_array($storedMethod, ['xendit', 'online'], true)) {
            return $storedMethod;
        }

        return $paymentData['channel_code']
            ?? (!empty($paymentData['bank_code']) ? $paymentData['bank_code'] . '_VA' : null)
            ?? (!empty($paymentData['qr_string']) || !empty($paymentData['qr_code']) ? 'QRIS' : null)
            ?? $paymentData['payment_method']['reusables']['payment_method_id']
            ?? $paymentData['payment_method']['type']
            ?? $paymentData['channel_properties']['channel_code']
            ?? null;
    }

    public function buildContext(string $type, $record): array
    {
        $default = [
            'title' => 'Pembayaran Berhasil',
            'subject_label' => null,
            'subject_name' => null,
            'period_label' => null,
            'thank_you' => 'Terima kasih atas kontribusi Anda.',
        ];

        if (!$record) {
            return $default;
        }

        if ($type === 'punia_pendatang') {
            $periodLabel = $this->formatPeriod($record->bulan_tahun);

            return [
                'title' => 'Pembayaran Iuran Krama Tamiu Berhasil',
                'subject_label' => 'Krama Tamiu',
                'subject_name' => $record->pendatang->nama ?? null,
                'period_label' => $periodLabel,
                'thank_you' => 'Terima kasih, pembayaran iuran krama tamiu telah berhasil diterima.',
            ];
        }

        if ($type === 'punia' && !empty($record->id_usaha)) {
            $usaha = Usaha::with('detail')->find($record->id_usaha);
            $periodLabel = null;
            if (!empty($record->bulan_punia) && !empty($record->tahun_punia)) {
                $periodLabel = $this->formatPeriod(sprintf('%04d-%02d', $record->tahun_punia, $record->bulan_punia));
            }

            return [
                'title' => 'Pembayaran Iuran Unit Usaha Berhasil',
                'subject_label' => 'Unit Usaha',
                'subject_name' => $usaha?->detail?->nama_usaha,
                'period_label' => $periodLabel,
                'thank_you' => 'Terima kasih, pembayaran iuran unit usaha telah berhasil diterima.',
            ];
        }

        return $default;
    }

    public function formatPeriod(?string $value): ?string
    {
        if (!$value) {
            return null;
        }

        if (preg_match('/^(\d{4})-(\d{2})$/', $value, $matches)) {
            $months = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
            ];

            $month = (int) $matches[2];

            return ($months[$month] ?? $matches[2]) . ' ' . $matches[1];
        }

        return $value;
    }
}