<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'total_balance',
        'interest_rate',
    ];
}
