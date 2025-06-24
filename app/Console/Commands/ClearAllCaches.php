<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearAllCaches extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:clear-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpa todos os caches do Laravel';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Limpando todos os caches...');

        // Limpa cache de configuração
        $this->call('config:clear');
        $this->info('✓ Cache de configuração limpo');

        // Limpa cache de rotas
        $this->call('route:clear');
        $this->info('✓ Cache de rotas limpo');

        // Limpa cache de views
        $this->call('view:clear');
        $this->info('✓ Cache de views limpo');

        // Limpa cache de aplicação
        $this->call('cache:clear');
        $this->info('✓ Cache de aplicação limpo');

        // Limpa cache de eventos
        $this->call('event:clear');
        $this->info('✓ Cache de eventos limpo');

        // Limpa cache de consultas
        $this->call('query:clear');
        $this->info('✓ Cache de consultas limpo');

        // Recarrega configurações
        $this->call('config:cache');
        $this->info('✓ Configurações recarregadas');

        $this->info('Todos os caches foram limpos com sucesso!');
    }
}
