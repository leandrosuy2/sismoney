<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SISMoney</title>

    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#4f46e5">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="SisMoney">
    <meta name="msapplication-TileColor" content="#4f46e5">
    <meta name="msapplication-tap-highlight" content="no">

    <!-- PWA Icons para iOS -->
    <link rel="apple-touch-icon" href="/icons/icon-192x192.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/icons/icon-192x192.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/icons/icon-192x192.png">
    <link rel="apple-touch-icon" sizes="167x167" href="/icons/icon-192x192.png">

    <!-- PWA Manifest -->
    <link rel="manifest" href="/manifest.json">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .bg-gradient {
            background: linear-gradient(135deg, #1a5f38 0%, #2d8a4f 100%);
        }
        .input-focus {
            transition: all 0.3s ease;
        }
        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(45, 138, 79, 0.2);
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Lado esquerdo - Imagem/Background -->
        <div class="hidden lg:flex lg:w-1/2 bg-gradient items-center justify-center p-12">
            <div class="max-w-lg text-center text-white">
                <i class="fas fa-hand-holding-usd text-6xl mb-6"></i>
                <h1 class="text-4xl font-bold mb-4">SISMoney</h1>
                <p class="text-lg opacity-90">Gerencie suas finanças com segurança e praticidade</p>
            </div>
        </div>

        <!-- Lado direito - Formulário -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
            <div class="max-w-md w-full">
                <!-- Logo para mobile -->
                <div class="lg:hidden text-center mb-8">
                    <i class="fas fa-hand-holding-usd text-4xl text-green-600 mb-2"></i>
                    <h1 class="text-2xl font-bold text-gray-900">SISMoney</h1>

                    <!-- Botão de instalação PWA (apenas mobile) -->
                    <button id="install-pwa-btn" class="mt-4 bg-indigo-600 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-indigo-700 transition-colors duration-200 hidden">
                        <i class="fas fa-download mr-2"></i>
                        Instalar App
                    </button>
                </div>

                <div class="bg-white rounded-2xl shadow-xl p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Bem-vindo de volta</h2>
                    <p class="text-gray-600 mb-8">Faça login para acessar sua conta</p>

                    <form action="{{ route('login') }}" method="POST" class="space-y-6">
                        @csrf

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">E-mail</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-gray-400"></i>
                                </div>
                                <input id="email" name="email" type="email" required
                                       class="input-focus block w-full pl-11 pr-4 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:border-green-500"
                                       placeholder="seu@email.com"
                                       value="{{ old('email') }}">
                            </div>
                            @error('email')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Senha</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                                <input id="password" name="password" type="password" required
                                       class="input-focus block w-full pl-11 pr-4 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:border-green-500"
                                       placeholder="Sua senha">
                            </div>
                            @error('password')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input id="remember" name="remember" type="checkbox"
                                       class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                <label for="remember" class="ml-2 block text-sm text-gray-700">
                                    Lembrar-me
                                </label>
                            </div>
                            <a href="#" class="text-sm font-medium text-green-600 hover:text-green-500">
                                Esqueceu a senha?
                            </a>
                        </div>

                        <button type="submit"
                                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Entrar
                        </button>
                    </form>

                    <div class="mt-8 text-center">
                        <p class="text-sm text-gray-600">
                            Não tem uma conta?
                            <a href="{{ route('register') }}" class="font-medium text-green-600 hover:text-green-500">
                                Registre-se
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PWA Script -->
    <script>
        // Verificar se é mobile
        const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);

        if (isMobile) {
            // Registrar Service Worker
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.register('/sw.js')
                    .then((registration) => {
                        console.log('Service Worker registrado:', registration.scope);
                    })
                    .catch((error) => {
                        console.log('Erro no Service Worker:', error);
                    });
            }

            // Mostrar botão de instalação
            const installBtn = document.getElementById('install-pwa-btn');
            if (installBtn) {
                installBtn.classList.remove('hidden');

                installBtn.addEventListener('click', () => {
                    if (/iPhone|iPad|iPod/i.test(navigator.userAgent)) {
                        alert('Para instalar no iPhone/iPad:\n\n1. Toque no botão de compartilhar (quadrado com seta)\n2. Selecione "Adicionar à Tela de Início"\n3. Toque em "Adicionar"');
                    } else {
                        alert('Para instalar no Android:\n\n1. Toque no menu (3 pontos)\n2. Selecione "Adicionar à tela inicial"\n3. Confirme a instalação');
                    }
                });
            }
        }
    </script>
</body>
</html>
