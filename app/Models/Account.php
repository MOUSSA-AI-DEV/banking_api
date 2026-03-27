<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'balance',
        'status',
        'type',
        'overdraft_limit',
        'interest_rate',
        'withdraw_limit',
        'guardian_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function outgoingTransfers()
    {
        return $this->hasMany(Transfer::class, 'source_account_id');
    }

    public function incomingTransfers()
    {
        return $this->hasMany(Transfer::class, 'destination_account_id');
    }

    public function coHolders()
    {
        return $this->belongsToMany(User::class, 'account_user')
                    ->withPivot('role')
                    ->withTimestamps();
    }
}
