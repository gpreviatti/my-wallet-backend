<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersHasWallets extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'wallet_id',
    ];
}
