<?php

use Illuminate\Foundation\Application;
use App\Http\Controllers\SettingsController;
use App\Models\Finances;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\FinancesController;
use Illuminate\Support\Facades\Auth;

// Rota pública de boas-vindas
Route::get('/', function () {
    return Inertia::render('Index', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth',
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();

        // Filtrando as contas conforme o papel do usuário
        if ($user->role === 'user') {
            $finances = Finances::where('user_id', $user->id)->get();
        } else {
            $finances = Finances::all();
        }

        return Inertia::render('Dashboard', [
            'finances' => $finances,
            'user' => $user,
        ]);
    })->name('dashboard');
});

// Rotas protegidas por papel "master"
Route::middleware(['auth'])->group(function () {
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/update', [SettingsController::class, 'update'])->name('settings.update');
});

// Route::middleware('auth')->group(function () {
    Route::get('/finances', [FinancesController::class, 'index']);
    Route::post('/finances', [FinancesController::class, 'store']);
    Route::put('/finances/{finance}', [FinancesController::class, 'update']);
    Route::delete('/finances/{finance}', [FinancesController::class, 'destroy']);
// });
