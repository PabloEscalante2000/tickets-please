<?php

use App\Http\Controllers\Api\v1\TicketController;
use App\Http\Controllers\Api\v1\AuthorsController;
use App\Http\Controllers\Api\v1\AuthorTicketsController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->apiResource("tickets",TicketController::class);
Route::middleware('auth:sanctum')->apiResource("authors",AuthorsController::class);
Route::middleware("auth:sanctum")->apiResource("authors.tickets",AuthorTicketsController::class);