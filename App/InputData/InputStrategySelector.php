<?php


namespace App\InputData;


use App\InputData\Strategies\InputFileInterface;
use App\InputData\Strategies\Csv;
use App\InputData\Strategies\Xml;
use App\System\File\Files;

class InputStrategySelector
{

    public static function detectStrategy(string $ext): ?InputFileInterface
    {
        switch ($ext) {

            case 'csv':
                return new Csv();

            case 'xml':
                return new Xml();
        }
        return null;
    }
}