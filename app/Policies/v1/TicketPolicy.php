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

    public function store(User $user, Ticket $ticket) {
        if($user->tokenCan(Abilities::CreateTicket)){
            return true;
        } 
        return false;
    }

    public function delete(User $user, Ticket $ticket) {
        if($user->tokenCan(Abilities::DeleteTicket)){
            return true;
        } else if($user->tokenCan(Abilities::DeleteOwnTicket)){
            return $ticket->user_id == $user->id;
        }
        return false;
    }

    public function replace(User $user, Ticket $ticket) {
        if($user->tokenCan(Abilities::ReplaceTicket)){
            return true;
        }
        return false;
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
