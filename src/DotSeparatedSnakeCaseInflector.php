<?php

namespace EventSauce\EventSourcing;

use function get_class;

class DotSeparatedSnakeCaseInflector implements ClassNameInflector
{
    public function classNameToType(string $className): string
    {
        return str_replace('\\_', '.', strtolower(preg_replace('/(.)(?=[A-Z])/u', '$1_', $className)));
    }

    public function typeToClassName(string $eventName): string
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', str_replace('.', '\\ ', $eventName))));
    }

    /**
     * @param object $instance
     * @return string
     */
    public function instanceToType($instance): string
    {
        return $this->classNameToType(get_class($instance));
    }
}
