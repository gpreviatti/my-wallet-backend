<?php

namespace App\Models\Repositories;

use App\Models\Entities\Entrace;
use Illuminate\Support\Str;

class EntraceRepository extends Repository
{
    /**
     * Constructor to bind model to repo
     */
    public function __construct()
    {
        parent::__construct(new Entrace);
    }

    /**
     * Create a new entrace
     *
     * @param array $data
     * @return array
     */
    public function createEntrace(array $data) : array
    {
        $category = (new CategoryRepository)->findByUuid($data['category_uuid']);
        if (!$category) {
            return ["success" => false, "message" => "Category not found"];
        }

        $walletRepository = new WalletRepository;
        $wallet = $walletRepository->findByUuid($data['wallet_uuid']);
        if (!$wallet) {
            return ["success" => false, "message" => "Wallet not found"];
        }

        $walletRepository->updateValue($wallet->uuid, $category->uuid, $data['value']);

        $data = $this->create([
            'uuid' => Str::uuid(),
            'wallet_id' => $wallet->id,
            'category_id' => $category->id,
            'description' => $data['description'],
            'value' => $data['value'],
            'observation' => $data['observation'] ?? '',
            'ticker' => $dta['ticker'] ?? '',
            'type' => $dta['type'] ?? ''
        ]);
        if ($data) {
            return [
                "success" => true,
                "message" => "Entrace created with success",
                "data" => $data
            ];
        }
        return ["success" => false, "message" => "Fail to create entrace"];
    }
}
