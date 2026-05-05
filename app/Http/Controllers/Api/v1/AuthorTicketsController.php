<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\filters\v1\TicketFilter;
use App\Http\Requests\Api\v1\ReplaceTicketRequest;
use App\Http\Requests\Api\v1\StoreTicketRequest;
use App\Http\Resources\v1\TicketResource;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AuthorTicketsController extends ApiController
{

    public function index($author_id, TicketFilter $filters){
        return TicketResource::collection(Ticket::where("user_id",$author_id)->filter($filters)->paginate());
    }

    public function store($author_id, StoreTicketRequest $request)
    {
        $model = [
            "title" => $request->input("data.attributes.title"),
            "description" => $request->input("data.attributes.description"),
            "status" => $request->input("data.attributes.status"),
            "user_id" => $author_id
        ];

        return new TicketResource(Ticket::create($model));
        
    }

    public function replace(ReplaceTicketRequest $request, $author_id, $ticket_id) {
        // PUT
        try {
            $ticket = Ticket::findOrFail($ticket_id);

            if($ticket->user_id === (int) $author_id){
                $model = [
                    "title" => $request->input("data.attributes.title"),
                    "description" => $request->input("data.attributes.description"),
                    "status" => $request->input("data.attributes.status"),
                    "user_id" => $request->input("data.relationships.author.data.id") 
                ];

                $ticket->update($model);
                return new TicketResource($ticket);
            }
            return $this->error("Ticket cannot be found", 404);
        } catch (ModelNotFoundException $th) {
            return $this->error("Ticket cannot be found", 404);
        }
    }

    public function destroy($author_id, $ticket_id)
    {
        try {
            $ticket = Ticket::findOrFail($ticket_id);

            if($ticket->user_id === (int) $author_id){
                $ticket->delete();
                return $this->ok("Ticket successfully deleted");
            }
            return $this->error("Ticket cannot be found", 404);
        } catch (ModelNotFoundException $th) {
            return $this->error("Ticket cannot be found", 404);
        }
    }
}
