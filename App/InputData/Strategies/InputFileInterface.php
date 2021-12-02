<?php


namespace App\InputData\Strategies;


use App\InputData\ReaderSettings;

interface InputFileInterface
{
    public function setPath(string $path);

    public function open();

    public function prepareDataForRead(ReaderSettings $settings);

    public function getData(): array;

    public function getRowIterator(): \Iterator;
}