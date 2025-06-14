<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Loan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CheckOverdueLoans extends Command
{
    protected $signature = 'loans:check-overdue';
    protected $description = 'Verifica parcelas vencidas e envia mensagens via WhatsApp';

    private $apiKey;
    private $instanceId;
    private $baseUrl;

    public function __construct()
    {
        parent::__construct();
        $this->apiKey = env('WHATSAPP_API_KEY');
        $this->instanceId = env('WHATSAPP_INSTANCE_ID');
        $this->baseUrl = env('WHATSAPP_API_URL');
    }

    public function handle()
    {
        $today = Carbon::today();
        Log::info('Iniciando verificação de empréstimos vencidos', ['data' => $today->format('Y-m-d')]);

        // Busca empréstimos com parcelas vencidas (até hoje)
        $loans = Loan::where('status', 'pendente')
            ->whereDate('dataPagamento', '<=', $today)
            ->get();

        Log::info('Empréstimos vencidos encontrados', ['quantidade' => $loans->count()]);

        foreach ($loans as $loan) {
            // Pega o número diretamente da tabela emprestimos
            $phone = $loan->telefone;

            if (empty($phone)) {
                Log::warning('Empréstimo sem telefone cadastrado:', [
                    'loan_id' => $loan->id,
                    'nome' => $loan->nome
                ]);
                continue;
            }

            $this->sendWhatsAppMessage($loan, $phone);
        }

        $this->info('Verificação de parcelas vencidas concluída.');
    }

    private function formatPhoneNumber($phone)
    {
        // Remove tudo que não for número
        $number = preg_replace('/[^0-9]/', '', $phone);

        // Se o número começar com 0, remove
        if (str_starts_with($number, '0')) {
            $number = substr($number, 1);
        }

        // Se o número não começar com 55, adiciona
        if (!str_starts_with($number, '55')) {
            $number = '55' . $number;
        }

        // Verifica se o número tem pelo menos 12 dígitos (55 + DDD + número)
        if (strlen($number) < 12) {
            Log::error('Número de telefone inválido (muito curto):', [
                'original' => $phone,
                'formatted' => $number,
                'length' => strlen($number),
                'expected_min' => 12
            ]);
            return null;
        }

        // Verifica se o número tem no máximo 14 dígitos (55 + DDD + número)
        if (strlen($number) > 14) {
            Log::error('Número de telefone inválido (muito longo):', [
                'original' => $phone,
                'formatted' => $number,
                'length' => strlen($number),
                'expected_max' => 14
            ]);
            return null;
        }

        // Verifica se o DDD é válido (após o 55)
        $ddd = substr($number, 2, 2);
        if (!in_array($ddd, ['11', '12', '13', '14', '15', '16', '17', '18', '19', '21', '22', '23', '24', '27', '28', '31', '32', '33', '34', '35', '37', '38', '41', '42', '43', '44', '45', '46', '47', '48', '49', '51', '53', '54', '55', '61', '62', '63', '64', '65', '66', '67', '68', '69', '71', '73', '74', '75', '77', '79', '81', '82', '83', '84', '85', '86', '87', '88', '89', '91', '92', '93', '94', '95', '96', '97', '98', '99'])) {
            Log::error('DDD inválido:', [
                'original' => $phone,
                'formatted' => $number,
                'ddd' => $ddd,
                'valid_ddds' => 'Lista de DDDs válidos do Brasil'
            ]);
            return null;
        }

        return $number;
    }

    private function sendWhatsAppMessage($loan, $phone)
    {
        try {
            $number = $this->formatPhoneNumber($phone);

            if (empty($number)) {
                Log::error('Número de telefone inválido para o empréstimo:', [
                    'loan_id' => $loan->id,
                    'nome' => $loan->nome,
                    'original_number' => $phone,
                    'data_pagamento' => $loan->dataPagamento,
                    'valor' => $loan->valor
                ]);
                return;
            }

            $message = "🚨 *ATENÇÃO: PARCELA VENCIDA* 🚨\n\n";
            $message .= "Prezado(a) " . $loan->nome . ",\n\n";
            $message .= "Informamos que sua parcela venceu em " . Carbon::parse($loan->dataPagamento)->format('d/m/Y') . ".\n\n";
            $message .= "Para regularizar sua situação, realize o pagamento através do PIX:\n";
            $message .= "📱 *Chave PIX:* " . $number . "\n\n";
            $message .= "Após o pagamento, envie o comprovante para este mesmo número.\n\n";
            $message .= "Agradecemos a atenção.\n";
            $message .= "Equipe Financeira";

            $payload = [
                'number' => $number,
                'text' => $message
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

            // Se o erro for apenas "exists: false", consideramos como sucesso
            if ($response->successful() ||
                ($response->status() === 400 &&
                 isset($response->json()['response']['message'][0]['exists']) &&
                 $response->json()['response']['message'][0]['exists'] === false)) {
                Log::info('Mensagem enviada com sucesso para o empréstimo:', [
                    'loan_id' => $loan->id,
                    'nome' => $loan->nome,
                    'number' => $number,
                    'data_pagamento' => $loan->dataPagamento
                ]);
            } else {
                Log::error('Erro ao enviar mensagem para o empréstimo:', [
                    'loan_id' => $loan->id,
                    'nome' => $loan->nome,
                    'number' => $number,
                    'error' => $response->json()['message'] ?? 'Erro desconhecido',
                    'response' => $response->json(),
                    'data_pagamento' => $loan->dataPagamento
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Erro ao enviar mensagem WhatsApp:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'loan_id' => $loan->id,
                'nome' => $loan->nome,
                'number' => $number ?? null,
                'data_pagamento' => $loan->dataPagamento
            ]);
        }
    }
}
