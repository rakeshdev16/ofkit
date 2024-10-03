<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

class TextMeService
{
    public function sendMessage($mobileNumber, $message)
    {
        try {

            $payload = [
                    'sms' => [
                    'user' => [
                        'username' => env('TEXTME_USERNAME'),
                    ],
                    'source' => env('TEXTME_VIRTUAL_NUMBER'),
                    'destinations' => [
                        'phone' => [
                            [
                                '$' => [
                                    'id' => $mobileNumber,
                                ],
                                '_' => $mobileNumber,
                            ],
                        ],
                    ],
                    'message' => $message,
                ],
            ];

            // Make the POST request
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('TEXTME_API_TOKEN'),
                'Content-Type' => 'application/json',
            ])->post(env('TEXTME_API_URL'), $payload);

            // Check the response
            if ($response->successful()) {
                return response()->json(['message' => 'SMS sent successfully!', 'data' => $response->json()]);
            } else {
                return response()->json(['message' => 'Failed to send SMS', 'error' => $response->json()], $response->status());
            }

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
