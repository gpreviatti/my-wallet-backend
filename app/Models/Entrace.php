<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entrace extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'wallet_id',
        'category_id',
        'description',
        'ticker',
        'tyoe',
        'observation',
        'value',
    ];
}
