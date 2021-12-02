<?php


namespace App\InputData\DTO;


use App\System\DTO\BasicDTO;

class TransactionDTO extends BasicDTO
{
    public \DateTime    $timestamp;
    public int          $person;
    public string       $isbn;
    public string       $action;

    public function __construct($obj)
    {
        $this->timestamp = $obj->timestamp;
        $this->person    = $obj->person;
        $this->isbn      = $obj->isbn;
        $this->action    = $obj->action;
    }

    public static function fromArray(array $arr): BasicDTO
    {
        $obj = (object) [
            'timestamp' => \DateTime::createFromFormat(\DateTimeInterface::ATOM, $arr[0]),
            'person'    => (int)$arr[1],
            'isbn'      => $arr[2],
            'action'    => $arr[3],
        ];
        return self::fromObject($obj);
    }

}