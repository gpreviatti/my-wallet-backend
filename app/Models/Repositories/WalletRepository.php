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

    /**
     * Delete resource by uuid
     *
     * @param string $uuid
     * @return void
     */
    public function deleteByUUid(string $uuid) : array
    {
        $wallet = $this->findByUuid($uuid);
        if ($this->repository->delete($wallet->id)) {
            return ["success" => true, "message" => "Wallet deleted with success"];
        };
        return ["success" => false, "message" => "Error to delete wallet"];
    }
}
