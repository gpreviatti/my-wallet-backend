<?php

namespace App\Models\Repositories;

use App\Models\Entities\UsersHaveWallets;

class UsersHaveWalletsRepository extends Repository
{
    /**
     * Constructor to bind model to repo
     */
    public function __construct()
    {
        parent::__construct(new UsersHaveWallets);
    }

    /**
     * Return user wallets or a specifc one by uuid
     *
     * @param string $uuid
     * @return array
     */
    public function getUserWallets(string $uuid = "") : array
    {
        $conditions['user_id'] = auth()->user()->id;
        if ($uuid) {
            $conditions['uuid'] = $uuid;
            return $this->model->where($conditions)->first()->toArray();
        }
        return $this->model->where($conditions)->get()->toArray();
    }
}
