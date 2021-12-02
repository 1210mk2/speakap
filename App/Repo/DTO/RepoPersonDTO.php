<?php


namespace App\Repo\DTO;


use App\InputData\DTO\BasicDTO;
use App\InputData\DTO\TransactionDTO;

class RepoPersonDTO extends BasicDTO
{
    public int          $key;

    public function __construct($obj)
    {
        $this->key    = $obj->key;
    }

    public static function fromObject(TransactionDTO $obj): self
    {
        return parent::fromObject((object) [
            'key'   => $obj->person,
        ]);
    }
}