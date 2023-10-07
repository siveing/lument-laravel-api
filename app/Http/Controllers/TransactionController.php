<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Wallet;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = $this->request->user();
        if ($user->can('SHOW_TRANSACTION')) {
            $start_date = $this->request->input('start_date');
            $end_date = $this->request->input('end_date');
            if (empty($start_date) && empty($end_date)) {
                $transactions = Transaction::paginate(10);
                return $this->ok('Get all transaction success', $transactions);
            } else {
                $transactions = Transaction::where('user_id', $user->id)->whereBetween('transaction_date', [$start_date, $end_date])->get();
                return $this->ok('Get all transaction success', $transactions);
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = $request->user();
        if ($user->can('STORE_TRANSACTION')) {

            $input = $request->all();

            $this->validate($request, [
                'name' => 'required',
                'amount' => 'required',
                'type' => 'required',
            ]);

            try {
                DB::beginTransaction();
                $transaction = new Transaction();
                $transaction->name = $input['name'];
                $transaction->amount = $input['amount'];
                $transaction->transaction_date = $request->has('transaction_date') ? $input['transaction_date'] : date('Y-m-d');
                $wallet = Wallet::where('user_id', $user->id)->first();
                if ($input['type'] == 'DEBIT') {
                    $wallet->debit($transaction);
                } else if ($input['type'] == 'CREDIT') {
                    $wallet->credit($transaction);
                } else {
                    return $this->badRequest('Transaction not available', $input);
                }
                $wallet->save();
                $user->balance = $wallet->ending_balance;
                $user->save();
                $transaction->user_id = $user->id;
                $transaction->wallet_id = $wallet->id;
                $transaction->type = $input['type'];
                $transaction->description = $transaction->name . ' with amount ' . $transaction->amount;
                $transaction->save();
                DB::commit();
                return $this->created('Create transaction success', $transaction);
            } catch (Exception $e) {
                DB::rollBack();
                return $this->internalServerError($e->getMessage());
            }
        } else {
            return $this->unauthorized();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();
        if ($user->can('SHOW_TRANSACTION')) {
            $transaction = Transaction::where('id', $id)->where('user_id', $user->id)->first();
            if (!$transaction) {
                return $this->badRequest('Transaction id ' . $id . ' not found');
            }
            return $this->ok('Get transaction by id success', $transaction);
        } else {
            return $this->unauthorized();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = $request->user();
        if ($user->can('STORE_TRANSACTION')) {
            $input = $request->all();

            $this->validate($request, [
                'type' => 'required',
            ]);

            try {
                DB::beginTransaction();
                $transaction = Transaction::where('id', $id)->where('user_id', $user->id)->first();
                if (!$transaction) {
                    return $this->badRequest('Transaction id ' . $id . ' not found');
                }
                $transaction->name = $input['name'];
                $transaction->amount = $input['amount'];
                $wallet = Wallet::where('user_id', $user->id)->first();
                if ($input['type'] == 'DEBIT') {
                    $wallet->debit($transaction);
                } else if ($input['type'] == 'CREDIT') {
                    $wallet->credit($transaction);
                } else {
                    return $this->badRequest('Transaction not available', $input);
                }
                $wallet->save();
                $user->balance = $wallet->ending_balance;
                $user->save();
                $transaction->user_id = $user->id;
                $transaction->wallet_id = $wallet->id;
                $transaction->type = $input['type'];
                $transaction->description = $transaction->name . ' with amount ' . $transaction->amount;
                $transaction->save();
                DB::commit();
                return $this->created('Update transaction success', $transaction);
            } catch (Exception $e) {
                DB::rollBack();
                return $this->internalServerError('Error update transaction');
            }
        } else {
            return $this->unauthorized();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = $this->request->user();
        if ($user->can('DESTROY_TRANSACTION')) {
            $transaction = Transaction::where('id', $id)->where('user_id', $user->id)->first();
            if (!$transaction) {
                return $this->badRequest('Transaction id ' . $id . ' not found');
            }
            $transaction->delete();
            return $this->ok('Delete transaction success');
        } else {
            return $this->unauthorized();
        }
    }
}
