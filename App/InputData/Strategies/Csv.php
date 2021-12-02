<?php


namespace App\InputData\Strategies;


use App\InputData\ReaderSettings;

class Csv extends InputFileCommon
{

    public function getData(): array
    {
        return $this->_files_instance->getCsvData();
    }

    public function getRowIterator(): \Iterator
    {
        return $this->_files_instance->getCsvRowGenerator();
    }

    public function prepareDataForRead(ReaderSettings $settings)
    {
        if ($settings->csv_skip_header) {
            $header_row_to_skip = $this->_files_instance->getCsvRow();
        }
    }
}