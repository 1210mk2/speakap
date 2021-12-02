<?php


namespace App\OutputData\Strategies;


use App\OutputData\DTO\OutputDTO;
use App\OutputData\WriterSettings;

class Txt extends OutputFileCommon
{

    public function saveData($data): bool
    {
        $fields = $data->getFields();
        $s = "";
        foreach ($fields as $field) {
            $s .= "\n" . $field . " = " . $data->$field;
        }
        return (bool) $this->_file_handler->put($s);
    }

    public function prepareDataForSave(WriterSettings $settings)
    {
    }
}