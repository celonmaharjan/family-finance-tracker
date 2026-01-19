<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'principal_amount',
        'interest_rate',
        'remaining_balance',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function loanPayments()
    {
        return $this->hasMany(LoanPayment::class);
    }

    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'related');
    }
}
