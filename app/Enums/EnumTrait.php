<?php

namespace App\Enums;

trait EnumTrait
{
    public function setAttribute($key, $value)
    {
        $enumFields = array_keys($this->enums);

        if (in_array($key, $enumFields)) {
            $enumObject = new $this->enums[$key]($value);

            $this->attributes[$key] = $enumObject->getValue();

            return $this;
        }

        return parent::setAttribute($key, $value);
    }
}
