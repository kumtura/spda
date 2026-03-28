<?php

namespace App\Http\Controllers;

use App\Models\Danapunia;
use App\Models\Sumbangan;
use App\Services\XenditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $xendit;

    public function __construct(XenditService $xendit)
    {
        $this->xendit = $xendit;
    }

public function initiate(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'order_id' => 'required|string',
            'type' => 'required|in:punia,donasi',
            'method' => 'required|string'
        ]);

        $method = $request->input('method');
        $order_id = $request->input('order_id');
        $amount = $request->input('amount');
        $type = $request->input('type');

        // Extract numeric ID dari order_id
        $id_segments = explode('-', $order_id);
        $id_numeric = $id_segments[1] ?? null;

        if (!$id_numeric) {
            return redirect()->back()->with('error', 'Format Order ID tidak valid.');
        }

        // Cari record di database
        $record = ($type === 'punia') ? Danapunia::find($id_numeric) : Sumbangan::find($id_numeric);

        if (!$record) {
            return redirect()->back()->with('error', 'Data transaksi tidak ditemukan.');
        }

        $external_id = $order_id . '-' . time();

        // Cek config
        if (!$this->xendit->isConfigured()) {
            return redirect()->back()->with('error', 'Layanan pembayaran sedang tidak tersedia.');
        }

        $response = null;

        // 1. Tembak Direct API (Tanpa fallback Invoice sama sekali)
        if (str_ends_with($method, '_VA')) {
            $bank_code = str_replace('_VA', '', $method); 
            $response = $this->xendit->createVA($external_id, $bank_code, $record->nama ?? 'Anonim', $amount);
            
        } elseif (in_array($method, ['ID_OVO', 'ID_DANA', 'ID_SHOPEEPAY', 'ID_LINKAJA', 'ID_GOPAY'])) {
            $response = $this->xendit->createEWalletCharge($external_id, $amount, $method, $order_id);
            
        } elseif (in_array($method, ['QRIS', 'ID_QRIS'])) {
            $response = $this->xendit->createQRCode($external_id, $amount);
            
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
        $record->update([
            'xendit_id' => $response['id'] ?? null,
            'metode' => $method,
            'payment_data' => json_encode($response), // Disimpan dengan rapi di sini
            'status_pembayaran' => 'pending'
        ]);

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
        $id_numeric = explode('-', $order_id)[1];

        $record = ($type === 'punia') ? Danapunia::find($id_numeric) : Sumbangan::find($id_numeric);
        if (!$record) return response()->json(['status' => 'error', 'message' => 'Record not found'], 404);

        $payment_data = json_decode($record->payment_data, true);
        
        // If it's VA, call Xendit Simulator
        if (str_contains($record->metode, '_VA')) {
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

        return response()->json(['status' => 'error', 'message' => 'Simulasi hanya tersedia untuk metode Virtual Account di mode Sandbox.'], 400);
    }

    public function showResult(Request $request)
    {
        $order_id = $request->order_id;
        $type = $request->type;
        $id_numeric = explode('-', $order_id)[1];

        $record = null;
        if ($type === 'punia') {
            $record = Danapunia::find($id_numeric);
        } else {
            $record = Sumbangan::find($id_numeric);
        }

        if (!$record || !$record->payment_data) {
            return redirect()->route('public.home')->with('error', 'Transaksi tidak ditemukan.');
        }

        $payment_data = json_decode($record->payment_data, true);
        $method = $record->metode;
        $is_sandbox = $this->xendit->isSandbox();
        
        // Get village data
        $settingsPath = storage_path('app/settings.json');
        $village = ['name' => 'SPDA'];
        if (file_exists($settingsPath)) {
            $village = json_decode(file_get_contents($settingsPath), true);
        }
        
        // Get payment channel info from database
        $channel = \App\Models\PaymentChannel::where('code', $method)->first();

        return view('front.pages.payment_result', compact('record', 'payment_data', 'method', 'village', 'order_id', 'type', 'is_sandbox', 'channel'));
    }

    public function checkStatus($order_id)
    {
        $id_numeric = explode('-', $order_id)[1];
        $type = str_contains($order_id, 'PN-') ? 'punia' : 'donasi';

        $record = ($type === 'punia') ? Danapunia::find($id_numeric) : Sumbangan::find($id_numeric);

        if (!$record) {
            return response()->json(['status' => 'not_found'], 404);
        }

        return response()->json([
            'status' => $record->status_pembayaran, // e.g., 'pending', 'completed'
        ]);
    }
}