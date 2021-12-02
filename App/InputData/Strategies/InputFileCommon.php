<?php


namespace App\InputData\Strategies;


use App\System\File\Files;

abstract class InputFileCommon implements InputFileInterface
{
    protected Files $_files_instance;

    public function __construct(Files $_files_instance)
    {
        $this->_files_instance = $_files_instance;
    }

    public function open(string $path): void
    {
        $this->_files_instance->open($path);
    }

    abstract public function getData(): array;

    abstract public function getRowIterator(): \Iterator;
}