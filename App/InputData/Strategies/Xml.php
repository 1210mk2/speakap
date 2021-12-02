<?php


namespace App\InputData\Strategies;


use App\InputData\ReaderSettings;
use \SimpleXMLReader;

class Xml extends InputFileCommon
{
    protected SimpleXMLReader $_file_handler;

    private string $xml_node_name;

    public function open(): void
    {
        $this->_file_handler = new SimpleXMLReader();
        $this->_file_handler->open($this->path);
    }

    public function getData(): array
    {
        $result = [];
        foreach ($this->getRowIterator() as $element) {
            $result[] = $element;
        }
        return $result;
    }

    public function getRowIterator(): \Iterator
    {
        while ($this->_file_handler->read()) {

            $name = $this->_file_handler->name;

            if ($name !== $this->xml_node_name) {
                continue;
            }

            $element = $this->_file_handler->expandSimpleXml();
            if (!$element) {
                continue;
            }

            yield [
                (string)$element->timestamp,
                (string)$element->person->attributes()->id,
                (string)$element->isbn,
                (string)$element->action->attributes()->type,
            ];
        }

        $this->_file_handler->close();
    }

    public function prepareDataForRead(ReaderSettings $settings)
    {
        $this->xml_node_name = $settings->xml_node_name;
    }
}