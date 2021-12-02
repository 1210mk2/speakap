<?php


namespace App\InputData;


use App\InputData\Strategies\InputFileInterface;

class InputStrategiesCollection
{
    private static self $_instance;

    private array $collection;

    protected static function getInstance(): self
    {
        if (!isset(static::$_instance)) {
            static::$_instance = new static();
        }
        return static::$_instance;
    }

    public static function add(InputFileInterface $strategy)
    {
        $self = self::getInstance();
        $self->collection[] = $strategy;
    }

    public static function getAll(): array
    {
        $self = self::getInstance();
        return $self->collection;
    }

}