<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\CreateTransactionRequest;
use App\Http\Requests\Transaction\UpdateTransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        try {
            return TransactionResource::collection(
                Transaction::filter($request->all())
                           ->paginate($request->paginate ?? 10)
            );
        }
        catch (\Throwable $th) {
            return response()->json([
                "message" => $th->getMessage()
            ]);
        }
    }

    public function store(
        CreateTransactionRequest $request
    )
    {
        try {
            $data = $request->validated();
            return new TransactionResource(Transaction::create($data));
        }
        catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }

    public function show(Transaction $transaction)
    {
        try {
            return new TransactionResource($transaction);
        }
        catch (\Throwable $th) {
            return response()->json([
                "message" => $th->getMessage()
            ]);
        }
    }

    public function update(
        UpdateTransactionRequest $request,
        Transaction $transaction
    )
    {
        try {
            $data = $request->validated();

            $arrayNew = array_filter($data, function ($value) {
                return !is_null($value);
            });

            $transaction->update($arrayNew);

            return new TransactionResource($transaction);
        }
        catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }

    public function destroy(Transaction $transaction)
    {
        try {
            return $transaction->delete();
        }
        catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }
}
