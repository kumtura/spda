<?php

namespace App\Http\Controllers;

use App\Models\Danapunia;
use App\Models\PuniaPendatang;
use App\Models\PuniaPura;
use App\Models\Sumbangan;
use App\Services\PaymentOrderService;
use App\Services\XenditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $xendit;
    protected $paymentOrders;

    public function __construct(XenditService $xendit, PaymentOrderService $paymentOrders)
    {
        $this->xendit = $xendit;
        $this->paymentOrders = $paymentOrders;
    }

public function initiate(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'order_id' => 'required|string',
            'type' => 'required|in:punia,donasi,punia_pura,punia_pendatang',
            'method' => 'required|string'
        ]);

        $method = $request->input('method');
        $order_id = $request->input('order_id');
        $amount = $request->input('amount');
        $type = $request->input('type');

        $id_numeric = $this->paymentOrders->extractNumericId($type, $order_id);

        if (!$id_numeric) {
            return redirect()->back()->with('error', 'Format Order ID tidak valid.');
        }

        $record = $this->paymentOrders->resolveRecord($type, $order_id);

        if (!$record) {
            return redirect()->back()->with('error', 'Data transaksi tidak ditemukan.');
        }

        if (in_array($record->status_pembayaran ?? null, ['completed', 'lunas'], true)) {
            return redirect()->route('public.payment_result', [
                'order_id' => $order_id,
                'type' => $type,
            ]);
        }

        $external_id = $order_id . '-' . time();

        // Cek config
        if (!$this->xendit->isConfigured()) {
            return redirect()->back()->with('error', 'Layanan pembayaran sedang tidak tersedia.');
        }

        $response = null;

        $redirect_url = route('public.payment_result', ['order_id' => $order_id, 'type' => $type]);

        $paymentContext = $this->paymentOrders->buildContext($type, $record);
        $payerName = $paymentContext['subject_name'] ?? $record->nama_donatur ?? $record->nama ?? 'Anonim';

        // 1. Tembak Direct API (Tanpa fallback Invoice sama sekali)
        if (str_ends_with($method, '_VA')) {
            $bank_code = str_replace('_VA', '', $method); 
            $response = $this->xendit->createVA($external_id, $bank_code, $payerName, $amount);
            
        } elseif (in_array($method, ['ID_OVO', 'ID_DANA', 'ID_SHOPEEPAY', 'ID_LINKAJA', 'ID_GOPAY'])) {
            $response = $this->xendit->createEWalletCharge($external_id, $amount, $method, $order_id, $redirect_url);
            
        } elseif (in_array($method, ['QRIS', 'ID_QRIS'])) {
            $response = $this->xendit->createQRCode($external_id, $amount, $redirect_url);
            
        } else {
            // Jika method aneh/tidak dikenali, tolak!
            Log::warning("Metode pembayaran tidak valid: {$method}", ['order_id' => $order_id, 'type' => $type]);
            return redirect()->back()->with('error', 'Metode pembayaran tidak valid.');
        }

        // 2. Catat payload di file log background (storage/logs/laravel.log)
        Log::info("=== PAYLOAD XENDIT {$method} ===", $response ?? []);

        // Cek apakah ada error dari Xendit
        if (isset($response['status']) && $response['status'] === 'error') {
            return redirect()->back()->with('error', 'Gagal memproses ke Xendit: ' . ($response['message'] ?? 'Kesalahan tidak diketahui.'));
        }

        // 3. Simpan data ke database
        $updateData = [
            'xendit_id' => $response['id'] ?? null,
            'payment_data' => json_encode($response),
            'status_pembayaran' => 'pending'
        ];
        // PuniaPura uses 'metode_pembayaran', others use 'metode'
        if ($type === 'punia_pura') {
            $updateData['metode_pembayaran'] = $method;
        } elseif ($type === 'punia_pendatang') {
            $updateData['metode_pembayaran'] = 'xendit';
        } else {
            $updateData['metode'] = $method;
        }
        $record->update($updateData);

        // 4. Redirect ke halaman Custom UI Anda (halaman instruksi/result)
        return redirect()->route('public.payment_result', [
            'order_id' => $order_id,
            'type' => $type
        ]);
    }

    public function simulate(Request $request)
    {
        $request->validate([
            'order_id' => 'required|string',
            'type' => 'required|string',
            'amount' => 'required|numeric'
        ]);

        if (!$this->xendit->isSandbox()) {
            return response()->json(['status' => 'error', 'message' => 'Simulation only allowed in Sandbox mode'], 403);
        }

        $order_id = $request->order_id;
        $type = $request->type;
        $amount = $request->amount;
        $record = $this->paymentOrders->resolveRecord($type, $order_id);
        if (!$record) return response()->json(['status' => 'error', 'message' => 'Record not found'], 404);

        $payment_data = $this->paymentOrders->decodePaymentData($record);
        $metode = $this->paymentOrders->extractMethodCode($record, $payment_data) ?? ($record->metode ?? $record->metode_pembayaran);
        
        // 1. If it's VA, call Xendit Simulator API
        if ($metode && str_contains($metode, '_VA')) {
            $external_id = $payment_data['external_id'] ?? null;
            if ($external_id) {
                $simResponse = $this->xendit->simulateVAPayment($external_id, $amount);
                Log::info("Xendit VA Simulation Triggered for {$external_id}:", ['response' => $simResponse]);
                return response()->json([
                    'status' => 'success', 
                    'message' => 'Permintaan simulasi telah dikirim ke Xendit. Mohon tunggu beberapa saat sampai webhook diterima sistem.'
                ]);
            } else {
                Log::warning("Xendit Simulation Skipped: No external_id found for record #{$order_id}");
                return response()->json(['status' => 'error', 'message' => 'ID Pembayaran tidak ditemukan untuk simulasi.'], 400);
            }
        }

        // 2. If it's E-Wallet or QRIS, simulation happens on the Mock Page
        if (in_array($metode, ['ID_OVO', 'ID_DANA', 'ID_SHOPEEPAY', 'ID_LINKAJA', 'ID_GOPAY', 'QRIS', 'ID_QRIS'])) {
            return response()->json([
                'status' => 'redirect_to_checkout',
                'message' => 'Untuk simulasi E-Wallet/QRIS, silakan gunakan tombol "Buka Aplikasi Pembayaran" untuk menuju ke halaman Simulator resmi Xendit.'
            ]);
        }

        return response()->json(['status' => 'error', 'message' => 'Simulasi belum tersedia untuk metode ini.'], 400);
    }

    public function showResult(Request $request)
    {
        $order_id = $request->order_id;
        $type = $request->type;
        $record = $this->paymentOrders->resolveRecord($type, $order_id);

        if (!$record) {
            return redirect()->route('public.home')->with('error', 'Transaksi tidak ditemukan.');
        }

        $payment_data = $this->paymentOrders->decodePaymentData($record);
        $method = $this->paymentOrders->extractMethodCode($record, $payment_data) ?? ($record->metode ?? $record->metode_pembayaran);
        $is_sandbox = $this->xendit->isSandbox();
        $isCompleted = in_array($record->status_pembayaran, ['completed', 'lunas'], true);
        $isManualFlow = in_array(strtolower((string) $method), ['cash', 'tunai', 'transfer', 'transfer_manual'], true);
        $showSuccessState = $isCompleted || $isManualFlow;
        $canDirectVerify = auth()->check()
            && (int) auth()->user()->id_level === 7
            && in_array($type, ['punia', 'punia_pendatang'], true)
            && in_array(strtolower((string) $method), ['transfer', 'transfer_manual'], true)
            && (($record->status_verifikasi ?? null) === 'pending');
        $verifiedByName = optional($record->verifiedBy ?? null)->name;
        $paymentContext = $this->paymentOrders->buildContext($type, $record);
        $receiptCode = null;
        $receiptUrl = null;
        $receiptDownloadUrl = null;

        if ($type === 'punia' && $record) {
            $receiptCode = 'PN-' . str_pad((string) $record->id_dana_punia, 6, '0', STR_PAD_LEFT);
            $receiptUrl = route('public.punia.receipt', ['code' => $receiptCode]);
            $receiptDownloadUrl = route('public.punia.receipt.download', ['code' => $receiptCode]);
        } elseif ($type === 'punia_pendatang' && $record) {
            $receiptCode = 'TM-' . str_pad((string) $record->id_punia_pendatang, 6, '0', STR_PAD_LEFT);
            $receiptUrl = route('public.punia.pendatang.receipt', ['code' => $receiptCode]);
            $receiptDownloadUrl = route('public.punia.pendatang.receipt.download', ['code' => $receiptCode]);
        }
        
        // Get village data
        $settingsPath = storage_path('app/settings.json');
        $village = ['name' => 'SPDA'];
        if (file_exists($settingsPath)) {
            $village = json_decode(file_get_contents($settingsPath), true);
        }
        
        // Get payment channel info from database
        $channel = \App\Models\PaymentChannel::where('code', $method)->first();

        return view('front.pages.payment_result', compact('record', 'payment_data', 'method', 'village', 'order_id', 'type', 'is_sandbox', 'channel', 'isCompleted', 'showSuccessState', 'canDirectVerify', 'verifiedByName', 'paymentContext', 'receiptCode', 'receiptUrl', 'receiptDownloadUrl'));
    }

    public function checkStatus($order_id)
    {
        $type = str_contains($order_id, 'PP-')
            ? 'punia_pura'
            : (str_contains($order_id, 'TM-') ? 'punia_pendatang' : (str_contains($order_id, 'PN-') ? 'punia' : 'donasi'));

        $record = $this->paymentOrders->resolveRecord($type, $order_id);

        if (!$record) {
            return response()->json(['status' => 'not_found'], 404);
        }

        $status = $record->status_pembayaran;
        if ($status === 'lunas') {
            $status = 'completed';
        }

        return response()->json([
            'status' => $status,
        ]);
    }
}