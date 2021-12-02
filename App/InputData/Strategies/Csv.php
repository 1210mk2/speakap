<?php


namespace App\InputData\Strategies;


use App\InputData\ReaderSettings;

class Csv extends InputFileCommon
{

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