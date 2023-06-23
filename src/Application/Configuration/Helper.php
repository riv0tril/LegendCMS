<?php

namespace App\Application\Configuration;

use App\Application\Configuration\Exception\AttributeException;

class Helper
{
    public static function validateProps(array $props, $dto): void
    {
        foreach ($props as $prop) {
            if (is_array($props)) {
                foreach ($prop as $subProp) {
                    if (!array_key_exists($subProp, $dto[$prop])) {
                        throw new AttributeException("Missing attribute: $dto[$prop] - " . $subProp);
                    }
                }
            }
            if (!array_key_exists($prop, $dto)) {
                throw new AttributeException("Missing attribute: $prop");
            }
        }

        if (count(array_keys($dto)) < count($props)) {
            throw new AttributeException("Too many attributes");
        }
    }
}
