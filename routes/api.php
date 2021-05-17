<?php

use App\Http\Controllers\Api\V1\AccountController;
use App\Http\Controllers\Api\V1\LoginController;
use App\Http\Controllers\Api\V1\TransactionController;
use Illuminate\Support\Facades\Route;


Route::post('v1/login', [LoginController::class, 'login'])->middleware("guest:sanctum");

Route::apiResource('v1/accounts', AccountController::class)
    ->only(["index", "show"])
    ->middleware("auth:sanctum");

Route::apiResource('transactions', TransactionController::class)
    ->only(["index"])
    ->middleware("auth:sanctum");

Route::post('transfer', [TransactionController::class, 'store'])->middleware("auth:sanctum");