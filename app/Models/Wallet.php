<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wallet extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'beginning_balance',
        'ending_balance',
        'debit',
        'credit',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function deposit($transaction)
    {
        $this->ending_balance = $this->ending_balance + $transaction->amount;
        $this->debit = $this->debit + $transaction->amount;
        return $this;
    }

    public function credit($transaction)
    {
        $this->ending_balance = $this->ending_balance - $transaction->amount;
        $this->credit = $this->credit + $transaction->amount;
        return $this;
    }
}
