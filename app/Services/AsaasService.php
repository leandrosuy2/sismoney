<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AsaasService
{
    private $apiKey;
    private $baseUrl;

    public function __construct()
    {
        $this->apiKey = env('ASAAS_API_KEY');
        $this->baseUrl = 'https://www.asaas.com/api/v3';
    }

    public function generatePixQRCode($value, $description = null)
    {
        try {
            Log::info('Gerando QR Code PIX via Asaas:', [
                'valor' => $value,
                'descricao' => $description
            ]);

            $response = Http::withHeaders([
                'accept' => 'application/json',
                'access_token' => $this->apiKey,
                'content-type' => 'application/json'
            ])->post($this->baseUrl . '/pix/qrCodes/static', [
                'addressKey' => env('ASAAS_PIX_KEY'),
                'value' => $value,
                'expirationDate' => now()->addDays(1)->format('Y-m-d'),
                'comment' => $description ?? 'Pagamento via PIX'
            ]);

            Log::info('Resposta da API Asaas:', [
                'status' => $response->status(),
                'body' => $response->json()
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Erro ao gerar QR Code PIX:', [
                'status' => $response->status(),
                'response' => $response->json()
            ]);

            return null;

        } catch (\Exception $e) {
            Log::error('Erro ao gerar QR Code PIX:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }
}
