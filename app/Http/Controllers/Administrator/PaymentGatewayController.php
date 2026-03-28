<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\PaymentSetting;
use App\Models\PaymentChannel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PaymentGatewayController extends Controller
{
    public function index()
    {
        $setting = PaymentSetting::where('gateway_name', 'xendit')->first();
        $channels = PaymentChannel::all();
        return view('admin.pages.settings.payment_gateway', compact('setting', 'channels'));
    }

    public function store(Request $request)
    {
        \Log::info('PaymentGateway Update Attempt', $request->all());
        
        $request->validate([
            'api_key' => 'nullable|string',
            'secret_key' => 'nullable|string',
            'webhook_token' => 'nullable|string',
            // Checkboxes send 'on' or nothing, so we don't strictly validate as boolean here
            // since we use $request->has() later anyway.
        ]);

        PaymentSetting::updateOrCreate(
            ['gateway_name' => 'xendit'],
            [
                'api_key' => $request->api_key,
                'secret_key' => $request->secret_key,
                'webhook_token' => $request->webhook_token,
                'is_active' => $request->has('is_active'),
                'is_sandbox' => $request->has('is_sandbox'),
            ]
        );

        return redirect()->back()->with('success', 'Pengaturan Payment Gateway berhasil diperbarui.');
    }

    public function updateChannel(Request $request, $id)
    {
        $channel = PaymentChannel::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string',
            'icon' => 'nullable|image|max:1024'
        ]);

        $data = [
            'name' => $request->name,
            'is_active' => $request->has('is_active')
        ];

        if ($request->hasFile('icon')) {
            // Delete old icon if it's local
            if ($channel->icon_url && !str_starts_with($channel->icon_url, 'http')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $channel->icon_url));
            }
            
            $path = $request->file('icon')->store('payment_icons', 'public');
            $data['icon_url'] = '/storage/' . $path;
        }

        $channel->update($data);

        return redirect()->back()->with('success', 'Metode pembayaran ' . $channel->name . ' berhasil diperbarui.');
    }
}
