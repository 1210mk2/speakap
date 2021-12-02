<?php


namespace App\InputData\Strategies;


use App\System\File\Files;

abstract class InputFileCommon implements InputFileInterface
{
    protected string $path;

    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    abstract public function getData(): array;

    abstract public function getRowIterator(): \Iterator;
}