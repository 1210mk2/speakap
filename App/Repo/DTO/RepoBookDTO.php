<?php


namespace App\Repo\DTO;


use App\InputData\DTO\BasicDTO;
use App\InputData\DTO\TransactionDTO;

class RepoBookDTO extends BasicDTO
{
    public int          $key;
    public string       $isbn;

    public function __construct($obj)
    {
        $this->key    = $obj->key;
        $this->isbn   = $obj->isbn;
    }

    public static function fromObject(TransactionDTO $obj): self
    {
        return parent::fromObject((object) [
            'key'   => crc32($obj->isbn),
            'isbn'  => $obj->isbn,
        ]);
    }
}