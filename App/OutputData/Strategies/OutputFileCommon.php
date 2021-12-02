<?php


namespace App\OutputData\Strategies;


use App\System\File\Files;

abstract class OutputFileCommon implements OutputFileInterface
{
    private string $path;

    protected Files $_file_handler;

    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    public function open(): void
    {
        $this->_file_handler = Files::open($this->path, 'w');
    }

    abstract public function saveData($data): bool;

}