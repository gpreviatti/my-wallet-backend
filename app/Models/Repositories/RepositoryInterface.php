<?php

namespace App\Models\Repositories;

interface RepositoryInterface
{
    public function all();

    public function create(array $data);

    public function update(array $data, int $id);

    public function updateByUuid(array $data, string $uuid);

    public function delete(int $id);

    public function show(int $id);

    public function findByUuid(string $uuid);
}
