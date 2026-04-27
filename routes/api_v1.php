<?php

use App\Http\Controllers\Api\v1\TicketController;
use App\Http\Controllers\Api\v1\UsersController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->apiResource("tickets",TicketController::class);
Route::middleware('auth:sanctum')->apiResource("users",UsersController::class);