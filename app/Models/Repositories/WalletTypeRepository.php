<?php

namespace App\Models\Repositories;

use App\Models\Entities\WalletType;

class WalletTypeRepository extends Repository
{
    /**
     * Constructor to bind model to repo
     */
    public function __construct()
    {
        parent::__construct(new WalletType);
    }
}
