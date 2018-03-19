<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

interface ClassNameInflector
{
    /**
     * @param string $className
     *
     * @return string
     */
    public function classNameToType(string $className): string;

    /**
     * @param string $eventName
     *
     * @return string
     */
    public function typeToClassName(string $eventName): string;

    /**
     * @param object $instance
     *
     * @return string
     */
    public function instanceToType($instance): string;
}
