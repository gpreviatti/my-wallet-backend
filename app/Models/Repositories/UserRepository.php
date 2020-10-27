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
     * @return array
     */
    public function createWithUuid(array $data) : array
    {
        $newUser = $this->create(array_merge(
            $data,
            ['password' => bcrypt($data['password']), 'uuid' => Str::uuid()]
        ));
        return $newUser->toArray();
    }

    /**
     * Return user profile with his wallets and custom categories
     *
     * @return array
     */
    public function profile() : array
    {
        return $this->model
        ->where('id', auth()->user()->id)
        ->with('wallets', 'wallets.type', 'categories')
        ->first()
        ->toArray();
    }
}
