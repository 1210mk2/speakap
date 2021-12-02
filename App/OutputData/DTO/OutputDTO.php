<?php


namespace App\OutputData\DTO;


use App\System\DTO\BasicDTO;

class OutputDTO extends BasicDTO
{
    public ?int    $winner_max_checkouts_person;
    public ?string $winner_longest_checkouts_book;
    public int     $current_checkedout_count;
    public ?int    $winner_max_books_possess_person;

    public function __construct($obj)
    {
        $this->winner_max_checkouts_person     = $obj->winner_max_checkouts_person;
        $this->winner_longest_checkouts_book   = $obj->winner_longest_checkouts_book;
        $this->current_checkedout_count        = $obj->current_checkedout_count;
        $this->winner_max_books_possess_person = $obj->winner_max_books_possess_person;
    }

}