<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'amount',
        'type',
        'account_id',
        'transfer_id'
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function transfer()
    {
        return $this->belongsTo(Transfer::class);
    }
}
