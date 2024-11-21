<?php

namespace App\Policies;

use App\Models\Finances;
use App\Models\User;

class FinancesPolicy
{
    // Usuário pode visualizar uma conta
    public function view(User $user, Finances $finance)
    {
        return $user->is_admin || $user->is_master || $finance->user_id === $user->id;
    }

    // Usuário pode atualizar uma conta
    public function update(User $user, Finances $finance)
    {
        return $user->is_admin || $user->is_master || $finance->user_id === $user->id;
    }

    // Usuário pode excluir uma conta
    public function delete(User $user, Finances $finance)
    {
        return $user->is_admin || $user->is_master || $finance->user_id === $user->id;
    }

    // Usuário pode criar uma conta
    public function create(User $user)
    {
        return true; // Todos os usuários podem criar
    }

    // Usuário pode visualizar todas as contas (apenas admin ou master)
    public function viewAny(User $user)
    {
        return $user->is_admin || $user->is_master;
    }
}
