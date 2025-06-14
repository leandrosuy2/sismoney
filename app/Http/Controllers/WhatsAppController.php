<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppController extends Controller
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

    public function index()
    {
        // Lista as instâncias disponíveis
        try {
            $response = Http::withHeaders([
                'apikey' => $this->apiKey,
                'Content-Type' => 'application/json'
            ])->get($this->baseUrl . '/instance/fetchInstances');

            Log::info('WhatsApp Instances:', [
                'status' => $response->status(),
                'body' => $response->json()
            ]);

            if ($response->successful()) {
                $instances = $response->json();
                if (!empty($instances)) {
                    // Atualiza o ID da instância para a primeira disponível
                    $this->instanceId = $instances[0]['id'];
                    Log::info('Using instance:', ['id' => $this->instanceId]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Error fetching instances:', [
                'error' => $e->getMessage()
            ]);
        }

        return view('whatsapp.index');
    }

    public function test(Request $request)
    {
        try {
            $number = preg_replace('/[^0-9]/', '', $request->number);

            if (empty($number)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Número inválido'
                ]);
            }

            // Adiciona o código do país se não existir
            if (!str_starts_with($number, '55')) {
                $number = '55' . $number;
            }

            $payload = [
                'number' => $number,
                'text' => $request->message
            ];

            $response = Http::withHeaders([
                'apikey' => $this->apiKey,
                'Content-Type' => 'application/json'
            ])->post($this->baseUrl . '/message/sendText/' . $this->instanceId, $payload);

            Log::info('WhatsApp API Request:', [
                'url' => $this->baseUrl . '/message/sendText/' . $this->instanceId,
                'headers' => [
                    'apikey' => $this->apiKey,
                    'Content-Type' => 'application/json'
                ],
                'body' => $payload
            ]);

            Log::info('WhatsApp API Response:', [
                'status' => $response->status(),
                'body' => $response->json()
            ]);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Mensagem enviada com sucesso'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Erro ao enviar mensagem: ' . ($response->json()['message'] ?? 'Erro desconhecido')
            ]);

        } catch (\Exception $e) {
            Log::error('WhatsApp API Error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao enviar mensagem: ' . $e->getMessage()
            ]);
        }
    }

    public function saveConfig(Request $request)
    {
        // Aqui você pode adicionar a lógica para salvar as configurações
        return redirect()->route('whatsapp.index')->with('success', 'Configurações salvas com sucesso!');
    }
}
