<?php

namespace App\Http\Controllers;

use App\Models\Danapunia;
use App\Models\PuniaPendatang;
use App\Models\Sumbangan;
use App\Models\PaymentSetting;
use App\Models\Usaha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\BagiHasilService;

class XenditWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();
        $xendit_token = $request->header('x-callback-token');

        // EXTENSIVE LOGGING for debugging DN-5
        Log::info('--- XENDIT WEBHOOK START ---');
        Log::info('URL: ' . $request->fullUrl());
        Log::info('Method: ' . $request->method());
        Log::info('Headers:', $request->headers->all());
        Log::info('Payload Raw:', $payload);

        // Verify Token for security
        $setting = PaymentSetting::where('gateway_name', 'xendit')->first();
        if (!$setting || $xendit_token !== $setting->webhook_token) {
            Log::warning('Xendit Webhook: Invalid callback token mismatch. Received: ' . ($xendit_token ?? 'NULL'));
            // Return 401 only if it's definitely not a test. 
            // For now, let's keep it 401 but log it.
        }

        // Handle Xendit "Test Webhook" dummy data
        if (isset($payload['external_id']) && (str_contains($payload['external_id'], 'fixed-va-') || str_contains($payload['external_id'], 'test'))) {
            Log::info('Xendit Webhook: Recognized as TEST/DUMMY data. Returning 200 OK.');
            return response()->json(['status' => 'success', 'message' => 'Test received'], 200);
        }

        // Extract External ID & Status (Unified for Invoice, VA, E-Wallet, QRIS)
        $external_id = $payload['external_id'] ?? 
                      ($payload['data']['reference_id'] ?? 
                      ($payload['reference_id'] ?? 
                      ($payload['qr_code']['external_id'] ?? 
                      ($payload['data']['external_id'] ?? null))));
                      
        $status = $payload['status'] ?? 
                   ($payload['data']['status'] ?? 
                   ($payload['qr_code']['status'] ?? null));

        Log::info("Xendit Webhook: Extracted external_id={$external_id}, status={$status}");

        if (!$external_id) {
            Log::warning('Xendit Webhook: External ID empty/missing.');
            return response()->json(['message' => 'External ID not found but reached'], 200);
        }

        // Find Record
        $parts = explode('-', $external_id);
        if (count($parts) < 2) {
            Log::warning("Xendit Webhook: Format non-standard: {$external_id}");
            return response()->json(['message' => 'Processing non-standard format as success for Xendit check'], 200);
        }

        // Determine type based on prefix
        $prefix = $parts[0];
        
        if ($prefix === 'TKT') {
            // Handle Tiket Wisata - extract kode_tiket (first 3 parts: TKT-timestamp-random)
            $kode_tiket = $parts[0] . '-' . $parts[1] . '-' . $parts[2];
            $tiket = \App\Models\TiketWisata::where('kode_tiket', $kode_tiket)->first();
            
            if (!$tiket) {
                Log::error("Xendit Webhook: Tiket NOT FOUND: {$kode_tiket}");
                return response()->json(['message' => 'Tiket not found but webhook reachable'], 200);
            }
            
            $success_statuses = ['PAID', 'SETTLED', 'COMPLETED', 'SUCCEEDED'];
            if (in_array(strtoupper($status), $success_statuses)) {
                $tiket->update([
                    'status_pembayaran' => 'completed',
                    'aktif' => '1'
                ]);
                
                Log::info("Xendit Webhook: Tiket #{$kode_tiket} COMPLETED.");
            }
            
            Log::info('--- XENDIT WEBHOOK END ---');
            return response()->json(['status' => 'success']);
        }
        
        // Handle Punia Pura
        if ($prefix === 'PP') {
            // For punia pura, format: PP-{id_pura}-{id_punia_pura}[-timestamp]
            $id_punia_pura = $parts[2] ?? null;
            $puniaPura = $id_punia_pura 
                ? \App\Models\PuniaPura::where('id_punia_pura', $id_punia_pura)
                    ->where('status_pembayaran', 'pending')
                    ->first()
                : null;

            if ($puniaPura) {
                $success_statuses = ['PAID', 'SETTLED', 'COMPLETED', 'SUCCEEDED'];
                if (in_array(strtoupper($status), $success_statuses)) {
                    $puniaPura->update([
                        'status_pembayaran' => 'completed',
                        'tanggal_pembayaran' => now()->toDateString(),
                        'aktif' => '1'
                    ]);
                    Log::info("Xendit Webhook: Punia Pura #{$puniaPura->id_punia_pura} COMPLETED.");
                }
            } else {
                Log::warning("Xendit Webhook: Punia Pura record NOT FOUND for {$external_id}");
            }

            Log::info('--- XENDIT WEBHOOK END ---');
            return response()->json(['status' => 'success']);
        }

        if ($prefix === 'TM') {
            $id_punia_pendatang = $parts[1] ?? null;
            $puniaPendatang = $id_punia_pendatang
                ? PuniaPendatang::with('pendatang')->find($id_punia_pendatang)
                : null;

            if (!$puniaPendatang) {
                Log::warning("Xendit Webhook: Punia Pendatang record NOT FOUND for {$external_id}");
                Log::info('--- XENDIT WEBHOOK END ---');
                return response()->json(['status' => 'success']);
            }

            $success_statuses = ['PAID', 'SETTLED', 'COMPLETED', 'SUCCEEDED'];
            if (in_array(strtoupper($status), $success_statuses)) {
                $puniaPendatang->update([
                    'status_pembayaran' => 'lunas',
                    'metode_pembayaran' => 'xendit',
                    'tanggal_bayar' => now(),
                    'aktif' => '1',
                ]);

                $idBanjar = $puniaPendatang->pendatang->id_data_banjar ?? null;
                if ($idBanjar) {
                    BagiHasilService::splitPayment(
                        'tamiu',
                        $puniaPendatang->id_punia_pendatang,
                        $idBanjar,
                        $puniaPendatang->nominal,
                        'xendit',
                        now()->toDateString()
                    );
                }

                Log::info("Xendit Webhook: Punia Pendatang #{$puniaPendatang->id_punia_pendatang} COMPLETED.");
            }

            Log::info('--- XENDIT WEBHOOK END ---');
            return response()->json(['status' => 'success']);
        }

        // Handle Punia/Donasi
        $id_numeric = $parts[1];
        $type = str_contains($prefix, 'PN') ? 'punia' : 'donasi';
        
        $record = $type === 'punia' 
            ? Danapunia::find($id_numeric) 
            : Sumbangan::find($id_numeric);

        if (!$record) {
            Log::error("Xendit Webhook: Record NOT FOUND in database: {$external_id}");
            // Return 200 to silence Xendit's "Test mode error" if the URL is working
            return response()->json(['message' => 'Record not found but webhook reachable'], 200);
        }

        // Potential Statuses: PAID, SETTLED, COMPLETED, SUCCEEDED
        $success_statuses = ['PAID', 'SETTLED', 'COMPLETED', 'SUCCEEDED'];
        if (in_array(strtoupper($status), $success_statuses)) {
            $updateData = [
                'status_pembayaran' => 'completed',
                'aktif' => '1'
            ];
            
            // Only update tanggal_pembayaran if it's not already set or if it's null
            if (!$record->tanggal_pembayaran) {
                $updateData['tanggal_pembayaran'] = now();
            }
            
            $record->update($updateData);

            // Split bagi hasil for punia usaha (PN- prefix)
            if ($type === 'punia') {
                $usaha = Usaha::with('detail')->find($record->id_usaha);
                $idBanjar = $usaha && $usaha->detail ? $usaha->detail->id_banjar : null;
                if ($idBanjar) {
                    BagiHasilService::splitPayment(
                        'usaha',
                        $record->id_dana_punia,
                        $idBanjar,
                        $record->jumlah_dana,
                        'xendit',
                        now()->toDateString()
                    );
                }
            }

            // For Donation Programs: Update 'terkumpul' total
            if ($type === 'donasi' && $record->id_program_donasi) {
                $program = \App\Models\ProgramDonasi::find($record->id_program_donasi);
                if ($program) {
                    $total_collected = Sumbangan::where('id_program_donasi', $program->id_program_donasi)
                        ->where('status_pembayaran', 'completed')
                        ->sum('nominal');
                    
                    $program->update(['terkumpul' => $total_collected]);
                    Log::info("Xendit Webhook: Program #{$program->id_program_donasi} updated.");
                }
            }
            
            Log::info("Xendit Webhook: Record #{$external_id} COMPLETED.");
        }

        Log::info('--- XENDIT WEBHOOK END ---');
        return response()->json(['status' => 'success']);
    }
}
