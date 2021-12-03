<?php


namespace App\Repo;


use App\InputData\DTO\TransactionDTO;
use App\Repo\DTO\RepoBookDTO;
use App\Repo\DTO\RepoPersonDTO;
use App\Repo\DTO\RepoTransactionDTO;

class TransactionRepo
{
    private array $ai_keyed_storage   = [];
    private array $person_keyed_index = [];
    private array $book_keyed_index   = [];

    private BookRepo   $_book_repo;
    private PersonRepo $_person_repo;

    public function __construct(BookRepo $_book_repo, PersonRepo $_person_repo)
    {
        $this->_book_repo   = $_book_repo;
        $this->_person_repo = $_person_repo;
    }

    public function save(TransactionDTO $transaction): void
    {
        $book_dto = RepoBookDTO::fromObject($transaction);
        $this->_book_repo->save($book_dto);

        $person_dto = RepoPersonDTO::fromObject($transaction);
        $this->_person_repo->save($person_dto);

        $transaction_dto = RepoTransactionDTO::fromObject($transaction/*, $inc_key*/, $book_dto);

        $primary_key = $this->saveWithAIKey($transaction_dto);
        $this->createPersonKeyIndex($transaction_dto, $primary_key);
        $this->createBookKeyIndex($transaction_dto, $primary_key);
    }

    private function saveWithAIKey(RepoTransactionDTO $transaction_dto): int
    {
        static $inc_key = 0;

        $this->ai_keyed_storage[$inc_key] = $transaction_dto;

        $current_key = $inc_key;
        $inc_key++;

        return $current_key;
    }

    public function getAll(): array
    {
        return $this->ai_keyed_storage;
    }

    public function getByPrimaryKey(int $primary_key): ?RepoTransactionDTO
    {
        return $this->ai_keyed_storage[$primary_key] ?? null;
    }

    public function getByPrimaryKeys(array $primary_keys): array
    {
        $result = [];
        foreach ($primary_keys as $primary_key) {
            $item = $this->getByPrimaryKey($primary_key);
            if (!$item) {
                continue;
            }
            $result[] = $item;
        }
        return $result;
    }

    public function getByPrimaryKeysGenerator(array $primary_keys): \Iterator
    {
        foreach ($primary_keys as $primary_key) {
            $item = $this->getByPrimaryKey($primary_key);
            if (!$item) {
                continue;
            }
            yield $item;
        }
    }

    private function createPersonKeyIndex(RepoTransactionDTO $transaction_dto, int $primary_key): void
    {
        $this->person_keyed_index[$transaction_dto->i_person][] = $primary_key;
    }

    public function getAllByPerson(int $i_person): ?array
    {
        $primary_keys = $this->person_keyed_index[$i_person] ?? [];
        return $this->getByPrimaryKeys($primary_keys);

    }

    public function getAllByPersonGenerator(int $i_person): \Iterator
    {
        $primary_keys = $this->person_keyed_index[$i_person] ?? [];
        return $this->getByPrimaryKeysGenerator($primary_keys);

    }

    private function createBookKeyIndex(RepoTransactionDTO $transaction_dto, int $primary_key): void
    {
        $this->book_keyed_index[$transaction_dto->i_isbn_key][] = $primary_key;
    }

    public function getAllByBook(int $i_isbn_key): ?array
    {
        $primary_keys = $this->book_keyed_index[$i_isbn_key] ?? null;
        return $this->getByPrimaryKeys($primary_keys);
    }

    public function getAllByBookGenerator(int $i_isbn_key): \Iterator
    {
        $primary_keys = $this->book_keyed_index[$i_isbn_key] ?? null;
        return $this->getByPrimaryKeysGenerator($primary_keys);
    }


}