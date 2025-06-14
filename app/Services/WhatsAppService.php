<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    private $apiKey;
    private $instanceId;
    private $baseUrl;

    public function __construct()
    {
        $this->apiKey = env('WHATSAPP_API_KEY');
        $this->instanceId = env('WHATSAPP_INSTANCE_ID');
        $this->baseUrl = env('WHATSAPP_API_URL');
    }

    public function sendMessage($phone, $message, $imageBase64 = null)
    {
        try {
            Log::info('Enviando mensagem WhatsApp:', [
                'telefone' => $phone,
                'tem_imagem' => !empty($imageBase64)
            ]);

            // Formata o número do telefone
            $phone = $this->formatPhoneNumber($phone);

            if (empty($phone)) {
                Log::error('Número de telefone inválido');
                return false;
            }

            $payload = [
                'phone' => $phone,
                'message' => $message
            ];

            // Se tiver imagem, adiciona ao payload
            if ($imageBase64) {
                $payload['image'] = $imageBase64;
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey
            ])->post($this->baseUrl . '/message/text', $payload);

            Log::info('Resposta do envio de mensagem:', [
                'status' => $response->status(),
                'body' => $response->json()
            ]);

            return $response->successful();

        } catch (\Exception $e) {
            Log::error('Erro ao enviar mensagem WhatsApp:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    private function formatPhoneNumber($phone)
    {
        // Remove caracteres não numéricos
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Verifica se o número tem o formato correto
        if (strlen($phone) < 10 || strlen($phone) > 11) {
            return null;
        }

        // Adiciona o código do país se necessário
        if (strlen($phone) === 11) {
            return '55' . $phone;
        }

        return null;
    }
}
