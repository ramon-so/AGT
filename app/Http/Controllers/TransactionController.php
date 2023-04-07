<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Carbon;

class TransactionController extends Controller
{

    /**
     *  @OA\Get(
     *  tags={"Transactions"},
     *  summary="Display a list of the transactions",
     *  description="get all transactions on database",
     *  path="/transactions",
     *  @OA\Response(
     *      response="200", description="List of tasks"
     *  )
     * )
     */
    public function index()
    {
        try {
            $transactions = Transaction::all()->map(function ($transaction) {
                $startDate = Carbon::parse($transaction->start_date);
                $endDate = Carbon::parse($transaction->end_date);
                $transaction->duration = $startDate->diffInDays($endDate);
                $transaction->result = $transaction->sale_value - $transaction->purchase_value;
                return $transaction;
            });

            return response()->json($transactions);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }


    /**
     *  @OA\Post(
     *  tags={"Transactions"},
     *  summary="Store a new transaction",
     *  description="Store a new transaction on database",
     *  path="/transactions",
     *  @OA\RequestBody(
     *      required=true,
     *      @OA\JsonContent(
     *          type="object",
     *          @OA\Property(property="start_date", type="date"),
     *          @OA\Property(property="end_date", type="date"),
     *          @OA\Property(property="purchase_value", type="double"),
     *          @OA\Property(property="sale_value", type="double"),
     *          @OA\Property(property="description", type="string"),
     *      )
     *  ),
     *  @OA\Response(
     *      response="200", description="Transaction stored",
     *      response="201", description="Transaction stored",
     *  )
     * )
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date',
                'purchase_value' => 'required|numeric',
                'sale_value' => 'required|numeric',
                'description' => 'required|string'
            ]);

            $transaction = new Transaction();
            $transaction->start_date = $request->input('start_date');
            $transaction->end_date = $request->input('end_date');
            $transaction->purchase_value = $request->input('purchase_value');
            $transaction->sale_value = $request->input('sale_value');
            $transaction->description = $request->input('description');
            $transaction->save();

            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);

            $transaction->duration = $endDate->diffInDays($startDate);
            $transaction->result = $transaction->sale_value - $transaction->purchase_value;

            return response()->json($transaction, 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    /**
     *  @OA\Put(
     *  tags={"Transactions"},
     *  summary="Update a transaction",
     *  description="Update a transaction on database",
     *  path="/transactions/{id}",
     *  @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the transaction",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *  @OA\RequestBody(
     *      required=true,
     *      @OA\JsonContent(
     *          type="object",
     *          @OA\Property(property="start_date", type="date"),
     *          @OA\Property(property="end_date", type="date"),
     *          @OA\Property(property="purchase_value", type="double"),
     *          @OA\Property(property="sale_value", type="double"),
     *          @OA\Property(property="description", type="string"),
     *      )
     *  ),
     *  @OA\Response(
     *      response="200", description="Transaction stored",
     *      response="201", description="Transaction stored",
     *  )
     * )
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date',
                'purchase_value' => 'required|numeric',
                'sale_value' => 'required|numeric',
                'description' => 'required|string'
            ]);

            $transaction = Transaction::findOrFail($id);
            $transaction->start_date = $request->input('start_date');
            $transaction->end_date = $request->input('end_date');
            $transaction->purchase_value = $request->input('purchase_value');
            $transaction->sale_value = $request->input('sale_value');
            $transaction->description = $request->input('description');
            $transaction->save();

            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);

            $transaction->duration = $endDate->diffInDays($startDate);
            $transaction->result = $transaction->sale_value - $transaction->purchase_value;

            return response()->json($transaction);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }   
    }

    /**
     *  @OA\Delete(
     *  tags={"Transactions"},
     *  summary="Update a transaction",
     *  description="Update a transaction on database",
     *  path="/transactions/{id}",
     *  @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the transaction",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *  @OA\Response(
     *      response="200", description="Transaction stored",
     *      response="201", description="Transaction stored",
     *  )
     * )
     */
    public function destroy($id)
    {
        try {
            $transaction = Transaction::findOrFail($id);
            $transaction->delete();

            return response()->json(['message' => 'Transaction deleted']);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }   
    }

}
