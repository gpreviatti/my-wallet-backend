<?php

namespace App\Models\Repositories;

use App\Models\Entities\Wallet;
use Illuminate\Support\Str;

class WalletRepository extends Repository
{
    /**
     * User have wallet repository
     *
     * @var UsersHaveWalletsRepository
     */
    private $userHaveWalletsRepository;

    /**
     * Constructor to bind model to repo
     */
    public function __construct()
    {
        parent::__construct(new Wallet);
        $this->userHaveWalletsRepository = new UsersHaveWalletsRepository;
    }

    /**
     * Return all entraces of a specific wallet
     *
     * @param string $uuid
     * @return array
     */
    public function entraces(string $uuid) : array
    {
        return $this->model->where(['uuid' => $uuid])
        ->with('entraces')
        ->first()
        ->toArray();
    }

    /**
     * Incress or decress wallet value
     *
     * @param string $walletId
     * @param string $categoryId
     * @param float $entraceValue
     * @return bool
     */
    public function updateValue(string $uuid, string $categoryUuid, float $value) : bool
    {
        /** update wallet value with type of category */
        $wallet = $this->findByUuid($uuid);
        $category = (new CategoryRepository)->findByUuid($categoryUuid);
        if (in_array($category->id, [1, 2, 3, 4])) {
            $newValue = $wallet->current_value + $value;
        } else {
            $newValue = $wallet->current_value - $value;
        }
        if (!$wallet->update(['current_value' => $newValue])) {
            return false;
        }
        return true;
    }

    /**
     * Create new wallet for loged user
     *
     * @param array $data
     * @return void
     */
    public function createUserWallet(array $data) : array
    {
        $wallet = $this->create(array_merge($data, ['uuid' => Str::uuid()]));
        if (!$wallet) {
            return ["success" => false, "message" => "Erro to create wallet"];
        }

        $userWallet = $this->userHaveWalletsRepository->create([
            "wallet_id" => $wallet->id,
            "user_id" => auth()->user()->id
        ]);

        if (!$userWallet) {
            return ["success" => false, "message" => "Erro to to relate user with new wallet"];
        }

        return [
            "success" => true,
            "message" => "Wallet created with success",
            "data" => $wallet->toArray()
        ];
    }
}
