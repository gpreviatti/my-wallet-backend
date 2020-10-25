<?php

namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'wallet_types_id',
        'name',
        'uuid',
        'description',
        'current_value',
        'due_date',
        'close_date',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id',
        'pivot',
        'wallet_types_id',
        'created_at',
        'updated_at'
    ];



    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function entraces()
    {
        return $this->hasMany(Entrace::class, 'wallet_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->hasOne(WalletType::class, 'id', 'wallet_types_id');
    }
}
