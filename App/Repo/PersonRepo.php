<?php


namespace App\Repo;


use App\Repo\DTO\RepoPersonDTO;

class PersonRepo
{
    private array $keyed_storage = [];

    public function save(RepoPersonDTO $person): void
    {
        $key = $person->key;
        $this->keyed_storage[$key] = $person;
    }

    public function getAll(): array
    {
        return $this->keyed_storage;
    }

    public function getByKey(int $key): ?RepoPersonDTO
    {
        return $this->keyed_storage[$key] ?? null;
    }
}