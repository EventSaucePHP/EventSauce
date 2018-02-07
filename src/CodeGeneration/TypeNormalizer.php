<?php

namespace EventSauce\EventSourcing\CodeGeneration;

use function ltrim;
use ReflectionClass;
use ReflectionException;

/**
 * @internal
 */
class TypeNormalizer
{
    public static function normalize(string $type): string
    {
        $type = ltrim($type, '\\');

        return static::isNativeType($type)
            ? $type
            : '\\'.$type;
    }

    public static function isNativeType(string $type)
    {
        try {
            new ReflectionClass(ltrim($type, '\\'));

            return false;
        } catch (ReflectionException $isNativeType) {
            return true;
        }
    }
}