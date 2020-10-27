<?php

namespace App\Models\Repositories;

use App\Models\Entities\WalletType;
use Illuminate\Support\Str;

class WalletTypeRepository extends Repository
{
    /**
     * Constructor to bind model to repo
     */
    public function __construct()
    {
        parent::__construct(new WalletType);
    }

    /**
     * Create a new wallet type
     *
     * @param array $data
     * @return void
     */
    public function createWalletType(array $data) : array
    {
        $wallet = $this->create(array_merge($data, ['uuid' => Str::uuid()]));
        if (!$wallet) {
            return ["success" => false, "message" => "Erro to create wallet type"];
        }

        return [
            "success" => true,
            "message" => "Wallet type created with success",
            "data" => $wallet->toArray()
        ];
    }
}
