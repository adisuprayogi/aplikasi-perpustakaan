<?php

namespace App\Policies;

use App\Models\User;
use App\Models\LoanRule;

class LoanRulePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view loan rules
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, LoanRule $loanRule): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only admin and branch admin can create loan rules
        return in_array($user->role, ['super_admin', 'branch_admin']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, LoanRule $loanRule): bool
    {
        return in_array($user->role, ['super_admin', 'branch_admin']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, LoanRule $loanRule): bool
    {
        return in_array($user->role, ['super_admin', 'branch_admin']);
    }
}
