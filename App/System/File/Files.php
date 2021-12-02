<?php

namespace App\System\File;


class Files
{

    private $handle;

    private $filename;

    private $with_info = false;

    private $autoclose = true;


    public function __destruct()
    {
        $this->close();

    }

    protected function getFullFilename()
    {
        return $this->getFullPath($this->filename);
//        return $this->getFullPath() . DIRECTORY_SEPARATOR . $this->filename;
    }

    protected function setFilename($filename): void
    {
        $this->filename = $filename;
        return;
        $path_array = explode(DIRECTORY_SEPARATOR, $filename);

        $this->filename = array_pop($path_array);

        $this->path = implode(DIRECTORY_SEPARATOR, $path_array);
    }

    protected function create($filename, $mode = 'w+')
    {
        $this->setFilename($filename);

        $this->handle = fopen($this->getFullFilename(), $mode);
//        $this->handle = fopen($this->getFullPath($filename), $mode);

        return $this;
    }

    protected function grow($filename)
    {
        $this->setFilename($filename);

        $this->handle = fopen($this->getFullFilename(), 'a+');
//        $this->handle = fopen($this->getFullPath($filename), 'a+');

        return $this;
    }

    protected function exist($filename, $absolute_path = false)
    {
        if (!$absolute_path) {
            $filename = $this->getFullPath($filename);
//            $filename = $this->getFullPath() . DIRECTORY_SEPARATOR . $filename;
        }

        return is_file($filename);

    }

    public function open(string $filename, string $mode = 'r'): void
    {

        $this->handle = fopen($filename, 'r');

    }

    public function info($filename)
    {
        return pathinfo($filename);
    }



    protected function autoclose()
    {
        $this->autoclose = true;
        return $this;
    }

    protected function noAutoclose()
    {
        $this->autoclose = false;
        return $this;
    }

    protected function close()
    {
        if ($this->handle) {
            if ($this->lock) {
                flock($this->handle,3);
            }
            fclose($this->handle);
            $this->handle = null;
        }
    }

    protected function saveJson($content)
    {
        return $this->put(json_encode($content));
    }

    protected function put($content)
    {
        $result = fwrite($this->handle, $content);

        if ($this->autoclose) {
            $this->close();
        }

        return $result;
    }

    public function all($path = '')
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
