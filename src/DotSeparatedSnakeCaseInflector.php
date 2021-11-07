<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use function get_class;

class DotSeparatedSnakeCaseInflector implements ClassNameInflector
{
    public function classNameToType(string $className): string
    {
        return str_replace('\\_', '.', strtolower((string) preg_replace('/(.)(?=[A-Z])/u', '$1_', $className)));
    }

    public function typeToClassName(string $eventType): string
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', str_replace('.', '\\ ', $eventType))));
    }

    public function instanceToType(object $instance): string
    {
        return $this->classNameToType(get_class($instance));
    }
}
