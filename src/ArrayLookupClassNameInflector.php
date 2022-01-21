<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

class ArrayLookupClassNameInflector implements ClassNameInflector
{
    public function __construct(protected array $lookup)
    {
    }

    public function classNameToType(string $className): string
    {
        $type = array_search($className, $this->lookup);
        if ($type === false) {
            throw new \Exception("Configure {$className} in event type lookup");
        }

        return (string) $type;
    }

    public function typeToClassName(string $eventType): string
    {
        if ( ! array_key_exists($eventType, $this->lookup)) {
            throw new \Exception("Type '{$eventType}' not configured in event type lookup");
        }

        return $this->lookup[$eventType];
    }

    public function instanceToType(object $instance): string
    {
        return $this->classNameToType(get_class($instance));
    }
}
