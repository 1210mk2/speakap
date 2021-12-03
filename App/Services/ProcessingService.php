<?php


namespace App\Services;


use App\InputData\InputStrategiesCollection;
use App\OutputData\DTO\OutputDTO;
use App\Repo\BookRepo;
use App\Repo\PersonRepo;
use App\Repo\TransactionRepo;

class ProcessingService
{
    private BookRepo $_book_repo;
    private PersonRepo $_person_repo;
    private TransactionRepo $_transaction_repo;

    private ?int    $winner_max_checkouts_person   = null;
    private ?string $winner_longest_checkouts_book = null;
    private int  $current_checkedout_count        = 0;
    private ?int $winner_max_books_possess_person = null;

    public function __construct(BookRepo $_book_repo, PersonRepo $_person_repo, TransactionRepo $_transaction_repo)
    {
        $this->_book_repo        = $_book_repo;
        $this->_person_repo      = $_person_repo;
        $this->_transaction_repo = $_transaction_repo;
    }

    public function process(array $collection)
    {
        foreach ($collection as $strategy) {

            $strategy->open();
            $strategy->prepareDataForRead(new \App\InputData\ReaderSettings());
            $data = $strategy->getRowIterator();
            foreach ($data as $row) {
                $transaction = \App\InputData\DTO\TransactionDTO::fromArray($row);
                $this->_transaction_repo->save($transaction);
            }
        }
    }
    public function calculate()
    {
//        $t_data = $this->_transaction_repo->getAll();
        $b_data = $this->_book_repo->getAll();
        $p_data = $this->_person_repo->getAll();

        $latest_checkout_i_timestamp_by_book = [];
        $checkout_time_sum_by_book           = [];
        foreach ($b_data as $key => $item) {
            $latest_checkout_i_timestamp_by_book[$key] = null;
            $checkout_time_sum_by_book[$key]           = 0;
        }

        $checkouts_count_by_person = [];
        $active_checkouts_by_person = [];

        foreach ($p_data as $person_key => $item) {

            $checkouts_count_by_person[$person_key]  = 0;
            $active_checkouts_by_person[$person_key] = 0;

            $transactions_by_person = $this->_transaction_repo->getAllByPersonGenerator($person_key);
            foreach ($transactions_by_person as $transaction) {

                $i_isbn_key  = $transaction->i_isbn_key;
                $i_timestamp = $transaction->i_timestamp;
                $i_action    = $transaction->i_action;

                if ($i_action == 0) {
                    $checkouts_count_by_person[$person_key]++;
                    $active_checkouts_by_person[$person_key]++;

                    $latest_checkout_i_timestamp_by_book[$i_isbn_key]    = $i_timestamp;
                    $checkout_time_sum_by_book[$i_isbn_key]             -= $i_timestamp;

                } elseif ($i_action == 1) {
                    $active_checkouts_by_person[$person_key]--;

                    $latest_checkout_i_timestamp_by_book[$i_isbn_key]    = null;
                    $checkout_time_sum_by_book[$i_isbn_key]             += $i_timestamp;
                }
            }
        }

        $max_time_sum      = 0;
        $winner_i_isbn_key = null;

        foreach ($checkout_time_sum_by_book as $i_isbn_key => $i_time_sum) {
            $latest_checkout_i_timestamp = (int)$latest_checkout_i_timestamp_by_book[$i_isbn_key];
            if ($latest_checkout_i_timestamp) {
                $i_time_sum += $latest_checkout_i_timestamp;
                $this->current_checkedout_count++;
            }
            if ($max_time_sum < $i_time_sum) {
                $max_time_sum = $i_time_sum;
                $winner_i_isbn_key = $i_isbn_key;
            }

        }

        $max_checkouts     = 0;
        $max_books_possess = 0;

        foreach ($checkouts_count_by_person as $i_person => $checkout_count) {
            if ($max_checkouts < $checkout_count) {
                $max_checkouts                          = $checkout_count;
                $this->winner_max_checkouts_person      = $i_person;
            }

            $active_checkout_count = $active_checkouts_by_person[$i_person];
            if ($max_books_possess < $active_checkout_count) {
                $max_books_possess                      = $active_checkout_count;
                $this->winner_max_books_possess_person  = $i_person;
            }
        }

        $book_data = $this->_book_repo->getByKey($winner_i_isbn_key);
        $this->winner_longest_checkouts_book = $book_data->isbn;

    }

    public function getOutputData(): OutputDTO
    {
        return OutputDTO::fromObject((object)[
            'winner_max_checkouts_person'     => $this->winner_max_checkouts_person,
            'winner_longest_checkouts_book'   => $this->winner_longest_checkouts_book,
            'current_checkedout_count'        => $this->current_checkedout_count,
            'winner_max_books_possess_person' => $this->winner_max_books_possess_person,
        ]);

    }
}