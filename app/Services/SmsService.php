<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    private $apiKey;

    public function __construct()
    {
        $this->apiKey = env('FAST2SMS_API_KEY');
        if (!$this->apiKey) {
            Log::error("Fast2SMS API Key is MISSING in environment.");
        } else {
            Log::debug("Fast2SMS API Key loaded. Length: " . strlen($this->apiKey) . ". Starts with: " . substr($this->apiKey, 0, 4));
        }
    }

    public function sendOtp($phone, $otp)
    {
        try {
            // Fast2SMS clean phone: remove non-numeric
            $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
            
            // If it starts with 91 and is 12 digits, strip the 91
            if (strlen($cleanPhone) == 12 && str_starts_with($cleanPhone, '91')) {
                $cleanPhone = substr($cleanPhone, 2);
            }

            // Fast2SMS API integration using GET (often more stable for direct OTP)
            Log::info("Attempting OTP dispatch to $cleanPhone using GET route:otp");
            $response = Http::withHeaders([
                'authorization' => $this->apiKey,
            ])->get('https://www.fast2sms.com/dev/bulkV2', [
                'variables_values' => (string)$otp,
                'route' => 'otp',
                'numbers' => $cleanPhone,
            ]);

            $result = $response->json();
            
            if (!$response->successful() || (isset($result['return']) && !$result['return'])) {
                 Log::error('Fast2SMS Error Details: ' . json_encode($result));
                 return false;
            }

            Log::info("OTP successfully dispatched to $cleanPhone");
            return true;
        } catch (\Exception $e) {
            Log::error('SMS Exception: ' . $e->getMessage());
            return false;
        }
    }
}
