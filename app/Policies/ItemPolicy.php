<?php

namespace App\Policies;

use App\Models\Item;
use App\Models\User;

class ItemPolicy
{
    /**
     * Determine whether the user can view any items.
     */
    public function viewAny(User $user)
    {
        // Permitir a administradores y profesores ver los ítems
        return $user->hasRole('admin') || $user->hasRole('profesor');
    }

    /**
     * Determine whether the user can view the item.
     */
    public function view(User $user, Item $item)
    {
        // Permitir a administradores y profesores ver los ítems
        return $user->hasRole('admin') || $user->hasRole('profesor');
    }

    /**
     * Determine whether the user can create items.
     */
    public function create(User $user)
    {
        // Permitir solo a administradores crear ítems
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the item.
     */
    public function update(User $user, Item $item)
    {
        // Permitir solo a administradores actualizar ítems
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the item.
     */
    public function delete(User $user, Item $item)
    {
        // Permitir solo a administradores eliminar ítems
        return $user->hasRole('admin');
    }
}
