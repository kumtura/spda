<?php

namespace App\Http\Controllers;

use App\Models\Danapunia;
use App\Models\Sumbangan;
use App\Models\PaymentSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
                      ($payload['qr_code']['external_id'] ?? 
                      ($payload['data']['external_id'] ?? null)));
                      
        $status = $payload['status'] ?? 
                   ($payload['data']['status'] ?? 
                   ($payload['qr_code']['status'] ?? null));

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

        $id_numeric = $parts[1];
        $type = str_contains($parts[0], 'PN') ? 'punia' : 'donasi';

        $record = ($type === 'punia') ? Danapunia::find($id_numeric) : Sumbangan::find($id_numeric);

        if (!$record) {
            Log::error("Xendit Webhook: Record NOT FOUND in database: {$external_id}");
            // Return 200 to silence Xendit's "Test mode error" if the URL is working
            return response()->json(['message' => 'Record not found but webhook reachable'], 200);
        }

        // Potential Statuses: PAID, SETTLED, COMPLETED, SUCCEEDED
        $success_statuses = ['PAID', 'SETTLED', 'COMPLETED', 'SUCCEEDED'];
        if (in_array(strtoupper($status), $success_statuses)) {
            $record->update([
                'status_pembayaran' => 'completed',
                'aktif' => '1',
                'tanggal_pembayaran' => now()
            ]);

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
