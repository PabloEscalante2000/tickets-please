<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Requests\Api\v1\StoreTicketRequest;
use App\Http\Requests\Api\v1\UpdateTicketRequest;
use App\Http\filters\v1\TicketFilter;
use App\Http\Requests\Api\v1\ReplaceTicketRequest;
use App\Http\Resources\v1\TicketResource;
use App\Models\Ticket;
use App\Models\User;
use App\Policies\v1\TicketPolicy;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TicketController extends ApiController
{

    protected $policyClass = TicketPolicy::class;

    /**
     * Display a listing of the resource.
     */
    public function index(TicketFilter $filters)
    {
        return TicketResource::collection(Ticket::filter($filters)->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketRequest $request)
    {
        try {
            $user = User::findOrFail($request->input("data.relationships.author.data.id"));
            // policy 
            $this->isAble("store",null);
            // TODO: create ticket
        } catch (ModelNotFoundException $th) {
            return $this->ok("User not found", [
                "error" => "The provided user id does not exists"
            ]);
        }

        return new TicketResource(Ticket::create($request->mappedAttributes()));
        
    }

    /**
     * Display the specified resource.
     */
    public function show($ticket_id)
    {
        try {
            $ticket = Ticket::findOrFail($ticket_id);

            if($this->include("author")){
                return new TicketResource($ticket->load("author"));
            }

            return new TicketResource($ticket);
        } catch (ModelNotFoundException $th) {
            return $this->error("Ticket cannot be found", 404);
        }
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, $ticket_id)
    {
        // PATCH
        try {
            $ticket = Ticket::findOrFail($ticket_id);

            // policy 
            $this->isAble("update",$ticket);

            $ticket->update($request->mappedAttributes());

            return new TicketResource($ticket);
        } catch (ModelNotFoundException $th) {
            return $this->error("Ticket cannot be found", 404);
        } catch (AuthorizationException $th) {
            return $this->error("You are not authorized to update that resource", 401);
        }
    }

    public function replace(ReplaceTicketRequest $request, $ticket_id) {
        // PUT
        try {
            $ticket = Ticket::findOrFail($ticket_id);

            // policy 
            $this->isAble("replace",$ticket);

            $ticket->update($request->mappedAttributes());

            return new TicketResource($ticket);
        } catch (ModelNotFoundException $th) {
            return $this->error("Ticket cannot be found", 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($ticket_id)
    {
        try {
            $ticket = Ticket::findOrFail($ticket_id);

            // policy 
            $this->isAble("delete",$ticket);

            $ticket->delete();

            return $this->ok("Ticket successfully deleted");
        } catch (ModelNotFoundException $th) {
            return $this->error("Ticket cannot be found", 404);
        }
    }
}
