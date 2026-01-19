<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_id',
        'amount',
        'payment_date',
        'payment_type',
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'related');
    }
}
