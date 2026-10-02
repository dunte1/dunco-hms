<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Invoice;
use Illuminate\Auth\Access\HandlesAuthorization;

class InvoicePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(['view patients', 'create invoices', 'edit invoices', 'add payments', 'view payment reports']);
    }

    public function view(User $user, Invoice $invoice): bool
    {
        return $user->hasAnyPermission(['view patients', 'create invoices', 'edit invoices', 'add payments']);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create invoices');
    }

    public function update(User $user, Invoice $invoice): bool
    {
        return $user->hasPermissionTo('edit invoices');
    }

    public function delete(User $user, Invoice $invoice): bool
    {
        return $user->hasPermissionTo('edit invoices');
    }
}
