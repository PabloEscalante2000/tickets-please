<?php

namespace App\Policies\v1;

use App\Models\Ticket;
use App\Models\User;
use App\Permisions\v1\Abilities;

class TicketPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function update(User $user, Ticket $ticket) {
        if($user->tokenCan(Abilities::UpdateTicket)){
            return true;
        } else if($user->tokenCan(Abilities::UpdateOwnTicket)){
            return $ticket->user_id == $user->id;
        }
        return false;
    }
}
