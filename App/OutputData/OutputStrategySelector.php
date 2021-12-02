<?php


namespace App\OutputData;


use App\OutputData\Strategies\OutputFileInterface;
use App\OutputData\Strategies\Json;
use App\OutputData\Strategies\Txt;
use App\System\File\Files;

class OutputStrategySelector
{

    public static function detectStrategy(string $ext): ?OutputFileInterface
    {
        switch ($ext) {

            case 'json':
                return new Json();

            case 'txt':
                return new Txt();
        }
        return null;
    }
}