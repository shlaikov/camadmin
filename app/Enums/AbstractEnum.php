<?php

namespace App\Enums;

use ReflectionClass;
use InvalidArgumentException;

abstract class AbstractEnum
{
    private $value;

    public function __construct($value)
    {
        if (!in_array($value, $this->getValues())) {
            throw new InvalidArgumentException;
        }

        $this->value = $value;
    }

    public static function getConstants()
    {
        return array_keys((new ReflectionClass(get_called_class()))->getConstants());
    }

    public static function getValues()
    {
        return array_values((new ReflectionClass(get_called_class()))->getConstants());
    }

    public function getValue()
    {
        return $this->value;
    }

    public function __toString()
    {
        return $this->value;
    }
}
