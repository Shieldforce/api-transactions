<?php


use App\Http\Controllers\Api\TransactionController;
use Illuminate\Support\Facades\Route;

Route::controller(TransactionController::class)
    ->middleware(["auth:sanctum"])
    ->name("panel.transaction.")
    ->prefix("transaction")
    ->group(function () {

    Route::get("/", 'index')
         ->name("index")
         /*->middleware(["ability:panel.transaction.index"])*/;

});
