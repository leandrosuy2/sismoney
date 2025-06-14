@extends('layouts.dashboard')

@section('title', 'Configuração WhatsApp')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg font-medium text-gray-900">
                        <i class="fab fa-whatsapp text-green-600 mr-2"></i>
                        Configuração WhatsApp
                    </h2>
                    <button onclick="openTestModal()" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                        <i class="fas fa-robot mr-2"></i>
                        Testar Bot
                    </button>
                </div>

                <form action="{{ route('whatsapp.save') }}" method="POST">
                    @csrf

                    <div class="space-y-6">
                        <!-- Mensagem de Boas-vindas -->
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Mensagem de Boas-vindas
                            </label>
                            <textarea name="mensagemBoasVindas" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-1 focus:ring-green-500 bg-white"
                                placeholder="Digite a mensagem de boas-vindas...">{{ old('mensagemBoasVindas', 'Olá! Seja bem-vindo ao nosso sistema de empréstimos.') }}</textarea>
                        </div>

                        <!-- Mensagem de Lembrete -->
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Mensagem de Lembrete
                            </label>
                            <textarea name="mensagemLembrete" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-1 focus:ring-green-500 bg-white"
                                placeholder="Digite a mensagem de lembrete...">{{ old('mensagemLembrete', 'Olá! Lembrete: seu empréstimo vence amanhã.') }}</textarea>
                        </div>

                        <!-- Mensagem de Atraso -->
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Mensagem de Atraso
                            </label>
                            <textarea name="mensagemAtraso" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-1 focus:ring-green-500 bg-white"
                                placeholder="Digite a mensagem de atraso...">{{ old('mensagemAtraso', 'Olá! Seu empréstimo está atrasado. Por favor, regularize o pagamento.') }}</textarea>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                <i class="fas fa-save mr-2"></i>
                                Salvar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Teste -->
<div id="testModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <h3 class="text-lg font-medium mb-4">Testar Bot WhatsApp</h3>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Número do WhatsApp
            </label>
            <input type="text" id="testNumber"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-1 focus:ring-green-500"
                placeholder="Ex: 5511999999999">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Mensagem de Teste
            </label>
            <textarea id="testMessage" rows="3"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-1 focus:ring-green-500"
                placeholder="Digite a mensagem de teste..."></textarea>
        </div>

        <div class="flex justify-end space-x-3">
            <button onclick="closeTestModal()" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                Cancelar
            </button>
            <button onclick="sendTestMessage()" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                Enviar
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openTestModal() {
        document.getElementById('testModal').classList.remove('hidden');
    }

    function closeTestModal() {
        document.getElementById('testModal').classList.add('hidden');
    }

    function sendTestMessage() {
        const number = document.getElementById('testNumber').value;
        const message = document.getElementById('testMessage').value;

        if (!number || !message) {
            alert('Preencha todos os campos');
            return;
        }

        const button = event.target;
        button.disabled = true;
        button.innerHTML = 'Enviando...';

        fetch('/api/whatsapp/test', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ number, message })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Mensagem enviada!');
                closeTestModal();
            } else {
                alert('Erro: ' + data.message);
            }
        })
        .catch(error => {
            alert('Erro ao enviar mensagem');
            console.error('Error:', error);
        })
        .finally(() => {
            button.disabled = false;
            button.innerHTML = 'Enviar';
        });
    }
</script>
@endpush
@endsection
