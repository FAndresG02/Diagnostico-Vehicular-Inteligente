<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class PushNotificationService
{
    public function send(string $title, string $body, string $code): void
    {
        if (!config('services.firebase.enabled')) {
            Log::info('FCM skipped (disabled): ' . $title . ' - ' . $body);
            return;
        }

        try {
            $this->sendFCM($title, $body, $code);
        } catch (\Exception $e) {
            Log::error('FCM send failed: ' . $e->getMessage());
        }
    }

    public function sendDtcNotification(array $codes): void
    {
        $this->send(
            title: 'Nuevo DTC registrado',
            body: 'Codigo(s): ' . implode(', ', $codes),
            code: $codes[0] ?? ''
        );
    }

    private function sendFCM(string $title, string $body, string $code): void
    {
        $projectId = config('services.firebase.project_id');
        $accessToken = $this->getAccessToken();

        $message = [
            'message' => [
                'topic' => 'todos',
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],
                'data' => [
                    'dtc' => $code,
                ],
                'android' => [
                    'notification' => [
                        'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                    ],
                ],
            ],
        ];

        $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $accessToken,
                'Content-Type: application/json',
            ],
            CURLOPT_POSTFIELDS => json_encode($message),
            CURLOPT_RETURNTRANSFER => true,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            Log::error('FCM error: ' . $response);
        }
    }

    private function getAccessToken(): string
    {
        $credentials = config('services.firebase.credentials');

        if (!$credentials || !is_array($credentials)) {
            throw new \RuntimeException('Firebase credentials not configured');
        }

        $client = new \Google_Client();
        $client->setAuthConfig($credentials);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $client->useApplicationDefaultCredentials(false);

        $token = $client->fetchAccessTokenWithAssertion();

        return $token['access_token'] ?? throw new \RuntimeException('Failed to get FCM access token');
    }
}
