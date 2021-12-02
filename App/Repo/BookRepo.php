<?php


namespace App\Repo;


use App\Repo\DTO\RepoBookDTO;

class BookRepo
{
    private array $keyed_storage = [];

    public function save(RepoBookDTO $book): void
    {
        $key = $book->key;
        $this->keyed_storage[$key] = $book;
    }

    public function getAll(): array
    {
        return $this->keyed_storage;
    }

    public function getByKey(int $key): ?RepoBookDTO
    {
        return $this->keyed_storage[$key] ?? null;
    }
}