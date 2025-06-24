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

    <!-- Botão flutuante de instalação PWA -->
    <div id="pwa-install-banner" class="fixed bottom-4 left-4 right-4 bg-indigo-600 text-white p-4 rounded-lg shadow-lg z-50">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-download mr-3 text-xl"></i>
                <div>
                    <p class="font-semibold">Instalar SisMoney</p>
                    <p class="text-sm opacity-90">Acesse mais rápido como app</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <button id="install-pwa-btn-banner" class="bg-white text-indigo-600 px-4 py-2 rounded-lg font-medium hover:bg-gray-100 transition-colors">
                    Instalar
                </button>
                <button id="close-pwa-banner" class="text-white hover:text-gray-200 transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- PWA Script -->
    <script>
        // Forçar mostrar o botão (teste)
        console.log('Script PWA carregado');

        // Verificar se é mobile de forma mais simples
        const isMobile = window.innerWidth <= 768 || /Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        console.log('É mobile?', isMobile);
        console.log('User Agent:', navigator.userAgent);

        // Controlar banner flutuante
        const pwaBanner = document.getElementById('pwa-install-banner');
        const installBtn = document.getElementById('install-pwa-btn-banner');
        const closeBtn = document.getElementById('close-pwa-banner');

        if (pwaBanner && installBtn && closeBtn) {
            console.log('Banner encontrado, configurando...');

            // Botão de instalar
            installBtn.addEventListener('click', () => {
                console.log('Botão instalar clicado');
                if (/iPhone|iPad|iPod/i.test(navigator.userAgent)) {
                    alert('Para instalar no iPhone/iPad:\n\n1. Toque no botão de compartilhar (quadrado com seta)\n2. Selecione "Adicionar à Tela de Início"\n3. Toque em "Adicionar"');
                } else {
                    alert('Para instalar no Android:\n\n1. Toque no menu (3 pontos)\n2. Selecione "Adicionar à tela inicial"\n3. Confirme a instalação');
                }
            });

            // Botão de fechar
            closeBtn.addEventListener('click', () => {
                console.log('Fechando banner');
                pwaBanner.style.display = 'none';
                // Salvar no localStorage para não mostrar novamente
                localStorage.setItem('pwa-banner-closed', 'true');
            });

            // Mostrar banner sempre em mobile (removendo verificação do localStorage)
            if (isMobile) {
                pwaBanner.style.display = 'block';
                console.log('Banner mostrado para mobile');
            } else {
                pwaBanner.style.display = 'none';
                console.log('Banner escondido para desktop');
            }
        } else {
            console.log('Banner NÃO encontrado!');
        }

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
    </script>
</body>
</html>
