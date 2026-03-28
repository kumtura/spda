<?php

namespace App\Services;

use App\Models\PaymentSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class XenditService
{
    protected $api_key;
    protected $secret_key;
    protected $base_url = 'https://api.xendit.co';

    public function __construct()
    {
        $setting = PaymentSetting::where('gateway_name', 'xendit')->where('is_active', true)->first();
        if ($setting) {
            $this->api_key = $setting->api_key;
            $this->secret_key = $setting->secret_key;
        }
    }

    public function isConfigured()
    {
        return !empty($this->secret_key);
    }

    public function isSandbox()
    {
        $setting = PaymentSetting::where('gateway_name', 'xendit')->first();
        return $setting ? (bool)$setting->is_sandbox : true;
    }

    /**
     * Create a VA for a donation
     */
    public function createVA($external_id, $bank_code, $name, $amount)
    {
        if (!$this->isConfigured()) {
            return ['status' => 'error', 'message' => 'Xendit not configured'];
        }

        try {
            $response = Http::withBasicAuth($this->secret_key, '')
                ->post($this->base_url . '/callback_virtual_accounts', [
                    'external_id' => $external_id,
                    'bank_code' => $bank_code,
                    'name' => $name,
                    'expected_amount' => (int)$amount,
                    'is_closed' => true,
                    'is_single_use' => true,
                    'expiration_date' => now()->addDays(1)->toISOString()
                ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Xendit VA Error: ' . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    /**
     * Create an E-Wallet charge (Gopay/QRIS/DANA)
     */
    public function createEWalletCharge($external_id, $amount, $channel_code, $reference_id)
    {
        if (!$this->isConfigured()) {
            return ['status' => 'error', 'message' => 'Xendit not configured'];
        }

        try {
            // Channel codes: ID_GOPAY, ID_DANA, ID_LINKAJA, ID_SHOPEEPAY
            $response = Http::withBasicAuth($this->secret_key, '')
                ->post($this->base_url . '/ewallets/charges', [
                    'reference_id' => $reference_id,
                    'currency' => 'IDR',
                    'amount' => (int)$amount,
                    'checkout_method' => 'ONE_TIME_PAYMENT',
                    'channel_code' => $channel_code,
                    'channel_properties' => [
                        'success_redirect_url' => route('public.home'),
                    ],
                    'metadata' => [
                        'external_id' => $external_id
                    ]
                ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Xendit E-Wallet Error: ' . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    /**
     * Create a Universal QRIS Code
     */
    public function createQRCode($external_id, $amount)
    {
        if (!$this->isConfigured()) {
            return ['status' => 'error', 'message' => 'Xendit not configured'];
        }

        try {
            $response = Http::withBasicAuth($this->secret_key, '')
                ->post($this->base_url . '/qr_codes', [
                    'external_id' => $external_id,
                    'type' => 'DYNAMIC',
                    'callback_url' => route('public.home'),
                    'amount' => (int)$amount,
                ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Xendit QRIS Error: ' . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    /**
     * Create a Xendit Invoice (Checkout Page)
     */
    public function createInvoice($external_id, $amount, $payer_email = null, $description = null)
    {
        if (!$this->isConfigured()) {
            return ['status' => 'error', 'message' => 'Xendit not configured'];
        }

        try {
            $response = Http::withBasicAuth($this->secret_key, '')
                ->post($this->base_url . '/v2/invoices', [
                    'external_id' => $external_id,
                    'amount' => (int)$amount,
                    'payer_email' => $payer_email,
                    'description' => $description ?? 'Pembayaran Donasi/Punia SPDA',
                    'should_send_email' => true,
                    'success_redirect_url' => route('public.payment_result', [
                        'order_id' => explode('-', $external_id)[0] . '-' . explode('-', $external_id)[1],
                        'type' => str_contains($external_id, 'PN') ? 'punia' : 'donasi'
                    ]),
                    'currency' => 'IDR'
                ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Xendit Invoice Error: ' . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    /**
     * Simulate a VA payment (Sandbox only)
     */
    public function simulateVAPayment($external_id, $amount)
    {
        if (!$this->isConfigured()) {
            return ['status' => 'error', 'message' => 'Xendit not configured'];
        }

        try {
            $response = Http::withBasicAuth($this->secret_key, '')
                ->post($this->base_url . "/callback_virtual_accounts/external_id={$external_id}/simulate_payment", [
                    'amount' => (int)$amount
                ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Xendit Simulation Error: ' . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
}
