<?php

namespace App\System\File;


class Files
{

    private $handle;

    private $autoclose = true;


    public function __destruct()
    {
        $this->close();

    }

    protected function exist($filename)
    {
        return is_file($filename);
    }

    public static function open(string $filename, string $mode = 'r'): self
    {
        $_instance = new self();

        $_instance->handle = fopen($filename, $mode);

        return $_instance;

    }

    public static function info($filename)
    {
        return pathinfo($filename);
    }

    protected function close()
    {
        if ($this->handle) {
            fclose($this->handle);
            $this->handle = null;
        }
    }

    public function saveJson($content)
    {
        return $this->put(json_encode($content));
    }

    public function put($content)
    {
        $result = fwrite($this->handle, $content);

        if ($this->autoclose) {
            $this->close();
        }

        return $result;
    }

    public static function all(string $path): array
    {

        $items = scandir($path);
        $items = array_slice($items, 2);
        return $items;
    }

    public function getCsvData(): array
    {
        $data = [];
        while (($row = fgetcsv($this->handle)) !== false) {
            $data[] = $row;
        }

        if ($this->autoclose) {
            $this->close();
        }

        return $data;
    }

    public function getCsvRow(): ?array
    {
        if (!feof($this->handle)) {
            return fgetcsv($this->handle);
        }
        return null;
    }

    public function getCsvRowGenerator(): \Iterator
    {
        while (!feof($this->handle) && $row = fgetcsv($this->handle)) {
            yield $row;
        }

        if ($this->autoclose) {
            $this->close();
        }
    }

}
