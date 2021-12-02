<?php


namespace App\InputData\Strategies;


use App\InputData\ReaderSettings;

interface InputFileInterface
{
    public function open(string $path);

    public function prepareDataForRead(ReaderSettings $settings);

    public function getData(): array;

    public function getRowIterator(): \Iterator;
}