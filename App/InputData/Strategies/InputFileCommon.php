<?php


namespace App\InputData\Strategies;


use App\System\File\Files;

abstract class InputFileCommon implements InputFileInterface
{
    private string $path;

    protected Files $_file_handler;

    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    public function open(): void
    {
        $this->_file_handler = Files::open($this->path);
    }

    abstract public function getData(): array;

    abstract public function getRowIterator(): \Iterator;
}