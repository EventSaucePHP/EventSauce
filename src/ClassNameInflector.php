<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

interface ClassNameInflector
{
    public function classNameToType(string $className): string;

    public function typeToClassName(string $eventName): string;

    public function instanceToType(object $instance): string;
}
