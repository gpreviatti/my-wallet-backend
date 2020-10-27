<?php

namespace App\Models\Repositories;

interface RepositoryInterface
{
    public function all();

    public function create(array $data);

    public function update(array $data, int $id) : bool;

    public function updateByUuid(array $data, string $uuid) : array;

    public function delete(int $id);

    public function deleteByUUid(string $uuid);

    public function show(int $id);

    public function findByUuid(string $uuid);
}
