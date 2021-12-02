<?php


namespace App\InputData;


use App\InputData\Strategies\InputFileInterface;
use App\InputData\Strategies\Csv;
use App\InputData\Strategies\Xml;
use App\System\File\Files;

class StrategySelector
{

    public static function detectStrategy(string $ext): ?InputFileInterface
    {
        switch ($ext) {

            case 'csv':
                return new Csv(new Files());

            case 'xml':
                return new Xml(new Files());
        }
        return null;
    }
}