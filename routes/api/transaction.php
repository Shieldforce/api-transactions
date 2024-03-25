<?php


use App\Http\Controllers\Api\TransactionController;
use Illuminate\Support\Facades\Route;

Route::controller(TransactionController::class)
    ->middleware(["auth:sanctum"])
    ->name("transaction.")
    ->prefix("transaction")
    ->group(function () {

    Route::get("/", 'index')
         ->name("index")
         ->middleware(["ability:api.transaction.index"]);

    Route::post("", 'store')
         ->name("store")
         ->middleware(["ability:api.transaction.store"]);

    Route::get("/{transaction}", 'show')
         ->name("show")
         ->middleware(["ability:api.transaction.show"]);

    Route::put("/{transaction}", 'update')
         ->name("update")
         ->middleware(["ability:api.transaction.update"]);

    Route::delete("/{transaction}", 'destroy')
         ->name("destroy")
         ->middleware(["ability:api.transaction.destroy"]);

    Route::post("/datatable", 'datatable')
         ->name("datatable")
         ->middleware(["ability:api.transaction.datatable"]);

});
