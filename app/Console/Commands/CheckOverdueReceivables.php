<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ContaReceber;
use App\Models\PixCode;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Services\WhatsAppService;
use App\Services\AsaasService;

class CheckOverdueReceivables extends Command
{
    protected $signature = 'receivables:check-overdue';
    protected $description = 'Verifica contas a receber vencidas e envia mensagens via WhatsApp';

    private $apiKey;
    private $instanceId;
    private $baseUrl;
    private $whatsappService;
    private $asaasService;

    public function __construct(WhatsAppService $whatsappService, AsaasService $asaasService)
    {
        parent::__construct();
        $this->apiKey = env('WHATSAPP_API_KEY');
        $this->instanceId = env('WHATSAPP_INSTANCE_ID');
        $this->baseUrl = env('WHATSAPP_API_URL');
        $this->whatsappService = $whatsappService;
        $this->asaasService = $asaasService;

        Log::info('Comando CheckOverdueReceivables inicializado', [
            'api_url' => $this->baseUrl,
            'instance_id' => $this->instanceId
        ]);
    }

    public function handle()
    {
        $today = now()->format('Y-m-d');
        Log::info('Iniciando verificação de contas a receber vencidas', ['data' => $today]);

        $contasVencidas = ContaReceber::with(['cliente'])
            ->where('status', 'Pendente')
            ->whereDate('data_pagamento', '<=', $today)
            ->get();

        Log::info('Contas vencidas encontradas', ['quantidade' => $contasVencidas->count()]);

        foreach ($contasVencidas as $conta) {
            try {
                Log::info('Processando conta vencida', [
                    'id' => $conta->id,
                    'cliente' => $conta->cliente->nome,
                    'valor' => $conta->valor,
                    'data_vencimento' => $conta->data_pagamento
                ]);

                // Gera QR Code do PIX
                $pixData = $this->asaasService->generatePixQRCode(
                    $conta->valor,
                    "Pagamento da conta #{$conta->id} - {$conta->empresa}"
                );

                if (!$pixData) {
                    Log::error('Erro ao gerar QR Code PIX para conta', ['conta_id' => $conta->id]);
                    continue;
                }

                // Salva o ID do PIX gerado
                PixCode::create([
                    'pix_id' => $pixData['id'],
                    'conta_receber_id' => $conta->id,
                    'valor' => $conta->valor,
                    'empresa' => $conta->empresa
                ]);

                // Monta a mensagem principal
                $mensagem = "Olá {$conta->cliente->nome}, tudo bem?\n\n";
                $mensagem .= "Gostaríamos de lembrar que você tem uma conta vencida conosco:\n\n";
                $mensagem .= "📅 Data de Vencimento: " . date('d/m/Y', strtotime($conta->data_pagamento)) . "\n";
                $mensagem .= "💰 Valor: R$ " . number_format($conta->valor, 2, ',', '.') . "\n";
                $mensagem .= "🏢 Empresa: {$conta->empresa}\n\n";
                $mensagem .= "Para facilitar o pagamento, você pode usar o PIX abaixo:\n\n";
                $mensagem .= "Ou escaneie o QR Code anexo.\n\n";
                $mensagem .= "Agradecemos a atenção e ficamos à disposição para qualquer dúvida.";

                // Envia a mensagem principal com o QR Code
                $this->sendWhatsAppMessage($conta->cliente->telefone, $mensagem, $pixData['encodedImage']);

                // Aguarda 2 segundos antes de enviar o código PIX
                sleep(2);

                // Envia o código PIX em uma mensagem separada
                $mensagemPix = "{$pixData['payload']}";
                $this->sendWhatsAppMessage($conta->cliente->telefone, $mensagemPix);

                Log::info('Mensagens enviadas com sucesso', [
                    'cliente' => $conta->cliente->nome,
                    'telefone' => $conta->cliente->telefone
                ]);

            } catch (\Exception $e) {
                Log::error('Erro ao processar conta vencida', [
                    'conta_id' => $conta->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }

        Log::info('Verificação de contas vencidas concluída');
    }

    private function formatPhoneNumber($phone)
    {
        Log::info('Formatando número de telefone:', ['telefone_original' => $phone]);

        // Remove todos os caracteres não numéricos
        $number = preg_replace('/[^0-9]/', '', $phone);
        Log::info('Número após remoção de caracteres especiais:', ['numero_limpo' => $number]);

        // Verifica se o número tem o formato correto
        if (strlen($number) < 10 || strlen($number) > 11) {
            Log::warning('Número de telefone com formato inválido:', [
                'numero_limpo' => $number,
                'tamanho' => strlen($number)
            ]);
            return null;
        }

        // Adiciona o código do país se necessário
        if (strlen($number) === 11) {
            $formattedNumber = '55' . $number;
            Log::info('Número formatado com sucesso:', [
                'numero_original' => $phone,
                'numero_formatado' => $formattedNumber
            ]);
            return $formattedNumber;
        }

        Log::warning('Número não pôde ser formatado:', [
            'numero_original' => $phone,
            'numero_limpo' => $number
        ]);
        return null;
    }

    private function sendWhatsAppMessage($phone, $message, $imageBase64 = null)
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
                'number' => $phone,
                'text' => $message
            ];

            // Se tiver imagem, adiciona ao payload
            if ($imageBase64) {
                $payload['image'] = $imageBase64;
            }

            $response = Http::withHeaders([
                'apikey' => $this->apiKey,
                'Content-Type' => 'application/json'
            ])->post($this->baseUrl . '/message/sendText/' . $this->instanceId, $payload);

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
}
