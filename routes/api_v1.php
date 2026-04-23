<?php

use App\Http\Controllers\Api\v1\TicketController;
use Illuminate\Support\Facades\Route;

Route::apiResource("tickets",TicketController::class);