<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Settings;
use Tymon\JWTAuth\Facades\JWTAuth;

class SettingsController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:api');
    // }

    public function index()
    {

        $user = auth()->user();


        if ($user->role !== 'master') {
            abort(403, 'Você não tem permissão para acessar esta página.');
        }

        $settings = Settings::first();

        return inertia('Settings/Settings', ['settings' => $settings]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();


        if ($user->role !== 'master') {
            abort(403, 'Você não tem permissão para realizar esta ação.');
        }

        $request->validate([
            'background_color' => 'required|string|max:7',
        ]);

        $settings = Settings::first();
        if (!$settings) {
            $settings = new Settings();
        }
        $settings->background_color = $request->input('background_color');
        $settings->save();

        // Redirecionar com mensagem de sucesso
        return redirect()->back()->with('success', 'Configuração salva com sucesso!');
    }
}
