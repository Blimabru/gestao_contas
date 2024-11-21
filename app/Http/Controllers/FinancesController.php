<?php

namespace App\Http\Controllers;

use App\Models\Finances;
use Illuminate\Http\Request;

class FinancesController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:api');
    // }


    /* public function index(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if ($user->role == 'admin' || $user->role == 'master') {

            $finances = Finances::with('user')->orderBy('due_date', 'desc')->get();
        } else {

            $finances = Finances::where('user_id', $user->id)->get();
        }

        return response()->json(['data' => $finances]);
    } */

    public function index(Request $request)
    {
        // Recuperar parâmetros de ordenação e filtragem
        $sortBy = $request->get('sortBy', 'due_date');
        $sortOrder = $request->get('sortOrder', 'desc');
        $filterStatus = $request->get('filterStatus', 'Todos');

        // Consultar as finanças com base nos filtros e ordenação
        $query = Finances::with('user');

        if ($filterStatus !== 'Todos') {
            $query->where('status', $filterStatus);
        }

        $finances = $query->orderBy($sortBy, $sortOrder)->get();

        return response()->json(['data' => $finances]);
    }

    public function store(Request $request)
    {

        $request->validate([
            'description' => 'required|string',
            'value' => 'required|numeric',
            'due_date' => 'required|date',
            'status' => 'required|in:Pendente,Pago,Atrasado',
            'type' => 'required|in:Pagar,Receber',
            'user_id' => 'required|exists:users,id'
        ]);

        $finance = new Finances();
        $finance->description = $request->description;
        $finance->value = $request->value;
        $finance->due_date = $request->due_date;
        $finance->status = $request->status;
        $finance->type = $request->type;
        $finance->user_id = $request->user_id;

        $finance->save();

        return response()->json($finance, 201);
    }

    public function update(Request $request, Finances $finance)
    {
        // $this->authorize('update', $finance); // Verificar se o usuário pode editar a conta

        $finance->update($request->all());

        return response()->json($finance);
    }

    // Excluir conta
    public function destroy(Finances $finance)
    {
        // $this->authorize('delete', $finance); // Verificar se o usuário pode excluir a conta

        $finance->delete();

        return response()->json(null, 204);
    }
}
