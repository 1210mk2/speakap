<?php


namespace App\OutputData\Strategies;


use App\OutputData\WriterSettings;

class Json extends OutputFileCommon
{

    public function saveData($data): bool
    {
        return (bool) $this->_file_handler->saveJson($data);
    }

    public function prepareDataForSave(WriterSettings $settings)
    {
    }
}