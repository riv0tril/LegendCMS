<?php

namespace App\Application\Configuration;

use App\Application\Configuration\Exception\AttributeException;

class Helper
{
    public static function validateProps(array $structure, array $array): bool
    {
        $errors = [];
        foreach ($structure as $key => $value) {
            if (is_array($value)) {
                if (!array_key_exists($key, $array)) $errors[] = false;
                Helper::validateProps($structure[$key], $array[$key], $errors);
            } else {
                if (!array_key_exists($value, $array)) $errors[] = false;
            }
        }
        return empty($errors);
    }
}
