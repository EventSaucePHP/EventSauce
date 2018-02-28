<?php

namespace EventSauce\EventSourcing;

interface ClassNameInflector
{
    public function classNameToType(string $className): string;
    public function typeToClassName(string $eventName): string;
    public function instanceToType($instance): string;
}