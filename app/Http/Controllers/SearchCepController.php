<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SearchCepController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:api');
    // }

    public function consultaCep(Request $request)
    {
        $request->validate([
            'cep' => 'required|regex:/^[0-9]{5}-?[0-9]{3}$/|max:10',
        ]);

        $cep = preg_replace('/\D/', '', $request->input('cep'));

        $response = Http::withOptions(['verify' => false])->get("https://viacep.com.br/ws/{$cep}/json/");


        if ($response->failed()) {
            return response()->json([
                'error' => 'Falha ao consultar o CEP',
                'details' => $response->json(),
            ], 500);
        }

        return response()->json($response->json());
    }
}
