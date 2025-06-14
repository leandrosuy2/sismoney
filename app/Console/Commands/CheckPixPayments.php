<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\PixCode;
use App\Models\ContaReceber;

class CheckPixPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pix:check-payments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica pagamentos PIX concluídos e atualiza o status das contas a receber.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $asaasToken = env('ASAAS_API_KEY');
        $asaasUrl = 'https://www.asaas.com/api/v3/pix/transactions';

        $response = Http::withHeaders([
            'accept' => 'application/json',
            'access_token' => $asaasToken,
        ])->get($asaasUrl);

        if (!$response->successful()) {
            Log::error('Erro ao consultar transações PIX na Asaas', ['status' => $response->status(), 'body' => $response->body()]);
            $this->error('Erro ao consultar transações PIX na Asaas.');
            return 1;
        }

        $data = $response->json();
        $count = 0;

        Log::info('Iniciando verificação de pagamentos PIX', ['total_transacoes' => count($data['data'] ?? [])]);

        if (isset($data['data']) && is_array($data['data'])) {
            foreach ($data['data'] as $transaction) {
                Log::info('Processando transação', [
                    'status' => $transaction['status'] ?? 'N/A',
                    'conciliationIdentifier' => $transaction['conciliationIdentifier'] ?? 'N/A',
                    'id' => $transaction['id'] ?? 'N/A'
                ]);

                if (isset($transaction['status']) && $transaction['status'] === 'DONE') {
                    // Primeiro tenta usar o conciliationIdentifier
                    $pixId = $transaction['conciliationIdentifier'] ?? null;

                    if (!$pixId) {
                        // Se não tiver, usa o ID da transação
                        $pixId = $transaction['id'] ?? null;
                    }

                    if ($pixId) {
                        Log::info('Buscando PIX no banco', ['pix_id' => $pixId]);

                        $pixCode = PixCode::where('pix_id', $pixId)->first();

                        if ($pixCode) {
                            Log::info('PIX encontrado no banco', [
                                'pix_id' => $pixId,
                                'conta_id' => $pixCode->conta_receber_id
                            ]);

                            $conta = ContaReceber::find($pixCode->conta_receber_id);

                            if ($conta) {
                                Log::info('Conta encontrada', [
                                    'conta_id' => $conta->id,
                                    'status_atual' => $conta->status
                                ]);

                                if ($conta->status !== 'pago') {
                                    $conta->status = 'pago';
                                    $conta->save();
                                    $count++;

                                    Log::info('Conta atualizada para pago', [
                                        'conta_id' => $conta->id,
                                        'pix_id' => $pixId
                                    ]);
                                } else {
                                    Log::info('Conta já está paga', ['conta_id' => $conta->id]);
                                }
                            } else {
                                Log::warning('Conta não encontrada', ['conta_id' => $pixCode->conta_receber_id]);
                            }
                        } else {
                            Log::info('PIX não encontrado no banco', ['pix_id' => $pixId]);
                        }
                    }
                }
            }
        }

        $this->info("Pagamentos PIX processados: $count");
        Log::info('Processamento de pagamentos PIX concluído', ['total' => $count]);
        return 0;
    }
}
