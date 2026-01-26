<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function interestRecords()
    {
        return $this->hasMany(InterestRecord::class);
    }

    public function getTotalDepositedAmountAttribute()
    {
        return $this->deposits->sum('amount');
    }

    public function getTotalWithdrawnAmountAttribute()
    {
        return $this->loans->sum('principal_amount');
    }

    public function getOutstandingLoanBalanceAttribute()
    {
        return $this->loans->where('status', 'active')->sum('remaining_balance');
    }

    public function getTotalInterestEarnedAttribute()
    {
        return $this->interestRecords->sum('amount');
    }

    /**
     * Get the total interest accrued on active loans (12% annual / 1% monthly).
     * This is the difference between what user owes now vs what they originally borrowed.
     */
    public function getLoanInterestAccruedAttribute()
    {
        $principalSum = $this->loans->where('status', 'active')->sum('principal_amount');
        $remainingSum = $this->loans->where('status', 'active')->sum('remaining_balance');
        return max(0, $remainingSum - $principalSum);
    }
}
