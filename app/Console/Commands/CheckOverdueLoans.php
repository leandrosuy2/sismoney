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

        // Busca empréstimos com parcelas vencidas hoje
        $loans = Loan::with('user')
            ->where('status', 'pendente')
            ->whereDate('dataPagamento', $today)
            ->get();

        foreach ($loans as $loan) {
            if (empty($loan->telefone)) {
                Log::warning('Empréstimo sem telefone cadastrado:', [
                    'loan_id' => $loan->id,
                    'user_id' => $loan->idUsuario
                ]);
                continue;
            }

            $this->sendWhatsAppMessage($loan);
        }

        $this->info('Verificação de parcelas vencidas concluída.');
    }

    private function sendWhatsAppMessage($loan)
    {
        try {
            $number = preg_replace('/[^0-9]/', '', $loan->telefone);

            if (empty($number)) {
                Log::error('Número de telefone inválido para o empréstimo: ' . $loan->id);
                return;
            }

            // Adiciona o código do país se não existir
            if (!str_starts_with($number, '55')) {
                $number = '55' . $number;
            }

            $message = "🚨 *ATENÇÃO: PARCELA VENCIDA* 🚨\n\n";
            $message .= "Prezado(a) cliente,\n\n";
            $message .= "Informamos que sua parcela venceu hoje, " . Carbon::parse($loan->dataPagamento)->format('d/m/Y') . ".\n\n";
            $message .= "Para regularizar sua situação, realize o pagamento através do PIX:\n";
            $message .= "📱 *Chave PIX:* 91980795456\n\n";
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

            if ($response->successful()) {
                Log::info('Mensagem enviada com sucesso para o empréstimo: ' . $loan->id);
            } else {
                Log::error('Erro ao enviar mensagem para o empréstimo: ' . $loan->id, [
                    'error' => $response->json()['message'] ?? 'Erro desconhecido'
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Erro ao enviar mensagem WhatsApp:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'loan_id' => $loan->id
            ]);
        }
    }
}
