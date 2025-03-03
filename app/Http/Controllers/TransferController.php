<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Transfer;
use Illuminate\Http\Request;
use DB;
use Validator;
use Auth;
use Cache;
use Log;
use App\Models\User;
use App\Jobs\ProcessTransfer;

class TransferController extends Controller
{

    public function transfer(Request $request)
    {
        $this->authorize('create', Transfer::class);

        $validator = Validator::make($request->all(), [
            'receiver_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return $this->errorWithData([], $validator->errors());
        }

        $sender = Auth::user();
        $receiver = User::findOrFail($request->receiver_id);
        $amount = $request->amount;
        $transferKey = "transfer_{$sender->id}_{$receiver->id}_{$amount}";

        // Idempotency check
        if (Cache::has($transferKey)) {
            return $this->errorWithData([], 'Duplicate transfer request detected.');

        }
        // Balance validation
        if ($sender->wallet->amount < $amount) {
            return $this->errorWithData([], 'Insufficient balance.');
        }

        DB::beginTransaction();
        try {
            $transfer = Transfer::create([
                'sender_id' => $sender->id,
                'receiver_id' => $receiver->id,
                'amount' => $request->amount,
                'status' => 'pending',
            ]);

            $transaction1 = Transaction::create([
                'user_id' => $sender->id,
                'amount' => $request->amount,
                'status' => 'pending',
                'description' => 'transfer',
                'transaction_type' => 'send'
            ]);

            $transaction2 = Transaction::create([
                'user_id' => $receiver->id,
                'amount' => $request->amount,
                'status' => 'pending',
                'description' => 'transfer',
                'transaction_type' => 'receive'
            ]);

            dispatch(new ProcessTransfer($transfer, $transaction1, $transaction2));
            DB::commit();
                // Store idempotency key temporarily
            Cache::put($transferKey, true, 60);

            return $this->successWithData([
                'transfer_id' => $transfer->id
            ], 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());
            return $this->errorWithData([], 'Transfer failed');
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transfer  $transfer
     * @return \Illuminate\Http\Response
     */
    public function show(Transfer $transfer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transfer  $transfer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transfer $transfer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transfer  $transfer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transfer $transfer)
    {
        //
    }
}
