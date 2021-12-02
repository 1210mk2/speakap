<?php


namespace App\Repo\DTO;


use App\InputData\DTO\BasicDTO;
use App\InputData\DTO\TransactionDTO;

class RepoTransactionDTO extends BasicDTO
{
//    public int          $key;
    public int          $i_timestamp;
    public int          $i_person;
    public int          $i_isbn_key;
    public int          $i_action;

    public function __construct($obj)
    {
//        $this->key         = $obj->key;
        $this->i_timestamp = $obj->i_timestamp;
        $this->i_person    = $obj->i_person;
        $this->i_isbn_key  = $obj->i_isbn_key;
        $this->i_action    = $obj->i_action;
    }

    public static function fromObject(TransactionDTO $obj/*, int $inc_key*/, RepoBookDTO $book_dto): self
    {
        return parent::fromObject((object) [
//            'key'         => $inc_key,
            'i_timestamp' => $obj->timestamp->getTimestamp(),
            'i_person'    => $obj->person,
            'i_isbn_key'  => $book_dto->key,
            'i_action'    => self::getActionToInt($obj->action),
        ]);
    }

    public static function getActionToInt(string $action): ?int
    {
        switch ($action) {
            case 'check-out':
                return 0;
            case 'check-in':
                return 1;
        }
        return null;
    }
}