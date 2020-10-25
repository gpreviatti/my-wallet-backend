<?php

namespace App\Models\Repositories;

use App\Models\Entities\User;
use Illuminate\Support\Str;

class UserRepository extends Repository
{
    /**
     * Constructor to bind model to repo
     */
    public function __construct()
    {
        parent::__construct(new User);
    }

    /**
     * Create a user
     *
     * @param array $data
     * @return void
     */
    public function createWithUuid(array $data)
    {
        return $this->create(array_merge(
            $data,
            ['password' => bcrypt($data['password']), 'uuid' => Str::uuid()]
        ));
    }

    /**
     * Return user profile with his wallets and custom categories
     *
     * @return void
     */
    public function profile()
    {
        return $this->model
        ->where('id', auth()->user()->id)
        ->with('wallets', 'wallets.type', 'categories')
        ->first();
    }
}
