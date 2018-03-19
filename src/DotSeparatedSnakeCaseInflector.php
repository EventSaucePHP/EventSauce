<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use function get_class;

class DotSeparatedSnakeCaseInflector implements ClassNameInflector
{
    /**
     * {@inheritdoc}
     */
    public function classNameToType(string $className): string
    {
        return str_replace('\\_', '.', strtolower(preg_replace('/(.)(?=[A-Z])/u', '$1_', $className)));
    }

    /**
     * {@inheritdoc}
     */
    public function typeToClassName(string $eventName): string
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', str_replace('.', '\\ ', $eventName))));
    }

    /**
     * {@inheritdoc}
     */
    public function instanceToType($instance): string
    {
        return $this->classNameToType(get_class($instance));
    }
}
