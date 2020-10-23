<?php

namespace App\Models\Entities;

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
        'uuid',
        'category_id',
        'description',
        'ticker',
        'type',
        'observation',
        'value',
        'created_at',
        'updated_at',
    ];
}
