<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmprestimoController;
use App\Http\Controllers\ContaPagarController;
use App\Http\Controllers\ContaReceberController;
use App\Http\Controllers\EmprestimoUsuarioController;
use App\Http\Controllers\WhatsAppController;
use App\Http\Controllers\RelatorioController;

// Rotas de Autenticação
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rotas Protegidas
Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Empréstimos
    Route::resource('emprestimos', EmprestimoController::class);

    // Contas a Pagar
    Route::resource('contas-pagar', ContaPagarController::class);

    // Contas a Receber
    Route::resource('contas-receber', ContaReceberController::class);

    // Pagamento e Abatimento de Empréstimos
    Route::post('/emprestimos/{emprestimo}/pagar', [EmprestimoController::class, 'pagar'])->name('emprestimos.pagar');
    Route::post('/emprestimos/{emprestimo}/abater', [EmprestimoController::class, 'abater'])->name('emprestimos.abater');

    // WhatsApp Routes
    Route::get('/whatsapp', [WhatsAppController::class, 'index'])->name('whatsapp.index');
    Route::post('/whatsapp/save', [WhatsAppController::class, 'saveConfig'])->name('whatsapp.save');
    Route::post('/api/whatsapp/test', [WhatsAppController::class, 'test'])->name('whatsapp.test');

    // Relatórios
    Route::get('/relatorios/emprestimos', [RelatorioController::class, 'emprestimos'])->name('relatorios.emprestimos');
    Route::get('/relatorios/emprestimos/pdf', [RelatorioController::class, 'pdf'])->name('relatorios.emprestimos.pdf');
});

// Rotas para acesso dos usuários aos empréstimos
Route::prefix('emprestimos/usuarios')->name('emprestimos.usuario.')->group(function () {
    Route::get('login', [EmprestimoUsuarioController::class, 'showLoginForm'])->name('login');
    Route::post('login', [EmprestimoUsuarioController::class, 'login']);
    Route::post('logout', [EmprestimoUsuarioController::class, 'logout'])->name('logout');
    Route::get('/', [EmprestimoUsuarioController::class, 'index'])->name('index')->middleware('auth');
});
