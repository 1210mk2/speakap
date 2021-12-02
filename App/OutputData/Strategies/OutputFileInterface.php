<?php


namespace App\OutputData\Strategies;


use App\OutputData\WriterSettings;
use App\System\DTO\BasicDTO;

interface OutputFileInterface
{
    public function setPath(string $path);

    public function open();

    public function prepareDataForSave(WriterSettings $settings);

    public function saveData(BasicDTO $data): bool;
}