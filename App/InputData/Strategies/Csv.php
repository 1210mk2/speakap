<?php


namespace App\InputData\Strategies;


use App\InputData\ReaderSettings;
use App\System\File\Files;

class Csv extends InputFileCommon
{
    protected Files $_file_handler;

    public function open(): void
    {
        $this->_file_handler = Files::open($this->path);
    }

    public function getData(): array
    {
        return $this->_file_handler->getCsvData();
    }

    public function getRowIterator(): \Iterator
    {
        return $this->_file_handler->getCsvRowGenerator();
    }

    public function prepareDataForRead(ReaderSettings $settings)
    {
        if ($settings->csv_skip_header) {
            $header_row_to_skip = $this->_file_handler->getCsvRow();
        }
    }
}