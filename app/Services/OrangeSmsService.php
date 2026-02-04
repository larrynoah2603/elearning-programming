<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OrangeSmsService
{
    public function sendPremiumActivation(string $phoneNumber, string $planName, Carbon $expiresAt): bool
    {
        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            return false;
        }

        $normalizedNumber = $this->normalizePhoneNumber($phoneNumber);
        if (!$normalizedNumber) {
            Log::warning('Orange SMS: numéro invalide pour envoi', ['phone' => $phoneNumber]);
            return false;
        }

        $message = sprintf(
            'Votre compte CodeLearn est maintenant Premium (%s). Expire le %s. Merci pour votre confiance.',
            $planName,
            $expiresAt->format('d/m/Y')
        );

        $baseUrl = rtrim(config('services.orange_sms.base_url', env('ORANGE_SMS_BASE_URL', 'https://api.orange.com')), '/');
        $sender = $this->formatSenderAddress(config('services.orange_sms.sender', env('ORANGE_SMS_SENDER', 'CODELEARN')));

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post($baseUrl . '/smsmessaging/v1/outbound/' . $sender . '/requests', [
                'outboundSMSMessageRequest' => [
                    'address' => 'tel:' . $normalizedNumber,
                    'senderAddress' => $sender,
                    'outboundSMSTextMessage' => [
                        'message' => $message,
                    ],
                ],
            ]);

        if (!$response->successful()) {
            Log::warning('Orange SMS: échec d\'envoi', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return false;
        }

        return true;
    }

    private function getAccessToken(): ?string
    {
        $clientId = config('services.orange_sms.client_id', env('ORANGE_SMS_CLIENT_ID'));
        $clientSecret = config('services.orange_sms.client_secret', env('ORANGE_SMS_CLIENT_SECRET'));
        $baseUrl = rtrim(config('services.orange_sms.base_url', env('ORANGE_SMS_BASE_URL', 'https://api.orange.com')), '/');

        if (!$clientId || !$clientSecret) {
            Log::warning('Orange SMS: identifiants manquants');
            return null;
        }

        return Cache::remember('orange_sms_token', now()->addMinutes(50), function () use ($clientId, $clientSecret, $baseUrl) {
            $response = Http::asForm()
                ->withBasicAuth($clientId, $clientSecret)
                ->post($baseUrl . '/oauth/v3/token', [
                    'grant_type' => 'client_credentials',
                ]);

            if (!$response->successful()) {
                Log::warning('Orange SMS: échec récupération token', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return null;
            }

            return $response->json('access_token');
        });
    }

    private function normalizePhoneNumber(string $phoneNumber): ?string
    {
        $digits = preg_replace('/\D+/', '', $phoneNumber);

        if (str_starts_with($digits, '0')) {
            $digits = '261' . substr($digits, 1);
        }

        if (!str_starts_with($digits, '261')) {
            return null;
        }

        return '+' . $digits;
    }

    private function formatSenderAddress(string $sender): string
    {
        $sender = trim($sender);
        if (str_starts_with($sender, 'tel:')) {
            return $sender;
        }

        if (preg_match('/^\+?[0-9]+$/', $sender)) {
            $sender = ltrim($sender, '+');
            return 'tel:+' . $sender;
        }

        return 'tel:' . $sender;
    }
}
