<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Heroicons -->
    <script src="https://unpkg.com/@heroicons/v2/24/outline/esm/index.js"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    <style>
        .sidebar-item {
            position: relative;
            transition: all 0.3s ease;
        }

        .sidebar-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 0;
            background-color: #4F46E5;
            transition: width 0.3s ease;
            border-radius: 0 4px 4px 0;
        }

        .sidebar-item:hover::before,
        .sidebar-item.active::before {
            width: 4px;
        }

        .sidebar-item:hover {
            transform: translateX(4px);
        }

        .sidebar-item.active {
            background-color: #374151;
            color: white;
        }

        .sidebar-item.active svg {
            color: white;
        }

        /* Sidebar Mobile Styles */
        #sidebar-mobile {
            transition: opacity 0.3s ease-in-out;
            opacity: 0;
        }

        #sidebar-mobile .relative {
            transform: translateX(-100%);
            transition: transform 0.3s ease-in-out;
        }

        #sidebar-mobile[style*="display: flex"] {
            opacity: 1;
        }

        #sidebar-mobile[style*="display: flex"] .relative {
            transform: translateX(0);
        }

        #sidebar-backdrop {
            transition: opacity 0.3s ease-in-out;
        }

        @keyframes slideIn {
            from {
                transform: translateX(-100%);
            }

            to {
                transform: translateX(0);
            }
        }

        .sidebar-mobile {
            animation: slideIn 0.3s ease-out;
        }
    </style>
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <!-- Sidebar Mobile (Overlay) -->
        <div id="sidebar-mobile" class="fixed inset-0 flex z-40 md:hidden" style="display: none;">
            <div id="sidebar-backdrop" class="fixed inset-0 bg-gray-600 bg-opacity-75"></div>

            <!-- Sidebar Mobile Content -->
            <div class="relative flex-1 flex flex-col max-w-xs w-full bg-gray-800">
                <div class="absolute top-0 right-0 -mr-12 pt-2">
                    <button id="close-sidebar" type="button" class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                        <span class="sr-only">Fechar menu</span>
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Logo e Nome do Sistema -->
                <div class="flex items-center justify-between h-16 flex-shrink-0 px-4 bg-gray-900">
                    <div class="flex items-center">
                        <svg class="h-8 w-8 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h1 class="ml-2 text-white text-xl font-bold">SisMoney</h1>
                    </div>
                </div>

                <!-- Menu Mobile -->
                <div class="flex-1 flex flex-col pt-5 pb-4 overflow-y-auto">
                    <nav class="mt-5 flex-1 px-2 space-y-1">
                        <a href="{{ route('dashboard') }}"
                            class="sidebar-item group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('dashboard') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            <svg class="mr-3 h-6 w-6 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Dashboard
                        </a>
                        <a href="{{ route('contas-pagar.index') }}"
                            class="sidebar-item group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('contas-pagar.*') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            <svg class="mr-3 h-6 w-6 {{ request()->routeIs('contas-pagar.*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Contas a Pagar
                        </a>

                        <a href="{{ route('contas-receber.index') }}"
                            class="sidebar-item group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('contas-receber.*') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            <svg class="mr-3 h-6 w-6 {{ request()->routeIs('contas-receber.*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Contas a Receber
                        </a>

                        <a href="{{ route('emprestimos.index') }}"
                            class="sidebar-item group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('emprestimos.*') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            <i class="fas fa-hand-holding-usd mr-3 {{ request()->routeIs('emprestimos.*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}"></i>
                            Empréstimos
                        </a>
                        @php $isAdmin = Auth::user() && Auth::user()->isAdmin; @endphp
                        @if($isAdmin)
                        <a href="{{ route('whatsapp.index') }}"
                            class="sidebar-item group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('whatsapp.*') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            <i class="fab fa-whatsapp mr-3 {{ request()->routeIs('whatsapp.*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}"></i>
                            WhatsApp
                        </a>
                        <a href="{{ route('users.index') }}"
                            class="sidebar-item group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('users.*') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            <i class="fas fa-users mr-3 {{ request()->routeIs('users.*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}"></i>
                            Usuários
                        </a>
                        @endif
                        <a href="{{ route('relatorios.emprestimos') }}"
                            class="sidebar-item group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('relatorios.emprestimos') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            <svg class="mr-3 h-6 w-6 {{ request()->routeIs('relatorios.emprestimos') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Relatórios
                        </a>
                        <a href="{{ route('profile.show') }}"
                            class="sidebar-item group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('profile.*') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            <i class="fas fa-user-circle mr-3 {{ request()->routeIs('profile.*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}"></i>
                            Perfil
                        </a>
                    </nav>
                </div>

                <!-- Perfil do Usuário Mobile -->
                <div class="flex-shrink-0 flex border-t border-gray-700 p-4">
                    <div class="flex-shrink-0 group block w-full">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-white">{{ Auth::user()->usuario }}</p>
                                <p class="text-xs font-medium text-gray-300">{{ Auth::user()->email }}</p>
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-gray-300 hover:text-white">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar para Desktop -->
        <div class="hidden md:flex md:w-64 md:flex-col md:fixed md:inset-y-0">
            <div class="flex-1 flex flex-col min-h-0 bg-gray-800">
                <!-- Logo e Nome do Sistema -->
                <div class="flex items-center justify-between h-16 flex-shrink-0 px-4 bg-gray-900">
                    <div class="flex items-center">
                        <svg class="h-8 w-8 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h1 class="ml-2 text-white text-xl font-bold">SisMoney</h1>
                    </div>
                </div>
                <!-- Menu -->
                <div class="flex-1 flex flex-col pt-5 pb-4 overflow-y-auto">
                    <nav class="mt-5 flex-1 px-2 space-y-1">
                        <a href="{{ route('dashboard') }}"
                            class="sidebar-item group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('dashboard') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            <svg class="mr-3 h-6 w-6 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Dashboard
                        </a>
                        <a href="{{ route('contas-pagar.index') }}"
                            class="sidebar-item group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('contas-pagar.*') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            <svg class="mr-3 h-6 w-6 {{ request()->routeIs('contas-pagar.*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Contas a Pagar
                        </a>

                        <a href="{{ route('contas-receber.index') }}"
                            class="sidebar-item group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('contas-receber.*') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            <svg class="mr-3 h-6 w-6 {{ request()->routeIs('contas-receber.*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Contas a Receber
                        </a>

                        <a href="{{ route('emprestimos.index') }}"
                            class="sidebar-item group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('emprestimos.*') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            <i class="fas fa-hand-holding-usd mr-3 {{ request()->routeIs('emprestimos.*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}"></i>
                            Empréstimos
                        </a>
                        @if($isAdmin)
                        <a href="{{ route('whatsapp.index') }}"
                            class="sidebar-item group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('whatsapp.*') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            <i class="fab fa-whatsapp mr-3 {{ request()->routeIs('whatsapp.*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}"></i>
                            WhatsApp
                        </a>
                        <a href="{{ route('users.index') }}"
                            class="sidebar-item group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('users.*') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            <i class="fas fa-users mr-3 {{ request()->routeIs('users.*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}"></i>
                            Usuários
                        </a>
                        @endif
                        <a href="{{ route('relatorios.emprestimos') }}"
                            class="sidebar-item group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('relatorios.emprestimos') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            <svg class="mr-3 h-6 w-6 {{ request()->routeIs('relatorios.emprestimos') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Relatórios
                        </a>
                        <a href="{{ route('profile.show') }}"
                            class="sidebar-item group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('profile.*') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            <i class="fas fa-user-circle mr-3 {{ request()->routeIs('profile.*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}"></i>
                            Perfil
                        </a>
                    </nav>
                </div>
                <!-- Perfil do Usuário -->
                <div class="flex-shrink-0 flex border-t border-gray-700 p-4">
                    <div class="flex-shrink-0 group block w-full">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-white">{{ Auth::user()->usuario }}</p>
                                <p class="text-xs font-medium text-gray-300">{{ Auth::user()->email }}</p>
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-gray-300 hover:text-white">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Conteúdo Principal -->
        <div class="md:pl-64 flex flex-col flex-1">
            <!-- Header -->
            <div class="sticky top-0 z-10 flex-shrink-0 flex h-16 bg-white shadow">
                <button id="open-sidebar" type="button"
                    class="md:hidden px-4 border-r border-gray-200 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                    <span class="sr-only">Abrir menu</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <div class="flex-1 px-4 flex justify-between">
                    <div class="flex-1 flex">
                        <h1 class="text-2xl font-semibold text-gray-900 self-center">@yield('title', 'Dashboard')</h1>
                    </div>
                    <div class="ml-4 flex items-center md:ml-6">
                        <!-- Notificações -->
                        <button type="button"
                            class="p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <span class="sr-only">Ver notificações</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Conteúdo da página -->
            <main class="flex-1">
                @yield('content')
            </main>
        </div>
    </div>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Sidebar Mobile JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarMobile = document.getElementById('sidebar-mobile');
            const openSidebarBtn = document.getElementById('open-sidebar');
            const closeSidebarBtn = document.getElementById('close-sidebar');
            const sidebarBackdrop = document.getElementById('sidebar-backdrop');

            // Função para abrir a sidebar
            function openSidebar() {
                sidebarMobile.style.display = 'flex';
                // Pequeno delay para permitir que o display seja aplicado antes da transição
                setTimeout(() => {
                    sidebarMobile.style.opacity = '1';
                    sidebarMobile.querySelector('.relative').style.transform = 'translateX(0)';
                }, 10);
                document.body.style.overflow = 'hidden'; // Previne scroll do body
            }

            // Função para fechar a sidebar
            function closeSidebar() {
                sidebarMobile.style.opacity = '0';
                sidebarMobile.querySelector('.relative').style.transform = 'translateX(-100%)';

                // Aguarda a transição terminar antes de esconder
                setTimeout(() => {
                    sidebarMobile.style.display = 'none';
                    document.body.style.overflow = ''; // Restaura scroll do body
                }, 300);
            }

            // Event listeners
            openSidebarBtn.addEventListener('click', openSidebar);
            closeSidebarBtn.addEventListener('click', closeSidebar);
            sidebarBackdrop.addEventListener('click', closeSidebar);

            // Fechar sidebar ao pressionar ESC
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && sidebarMobile.style.display === 'flex') {
                    closeSidebar();
                }
            });

            // Fechar sidebar ao clicar em um link do menu (em dispositivos móveis)
            const mobileMenuLinks = sidebarMobile.querySelectorAll('a');
            mobileMenuLinks.forEach(link => {
                link.addEventListener('click', function() {
                    // Pequeno delay para permitir que o link seja processado
                    setTimeout(closeSidebar, 100);
                });
            });
        });
    </script>

    @stack('scripts')
</body>

</html>
