<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionType;
use ReflectionUnionType;

class InflectHandlerMethodsFromType implements HandleMethodInflector
{
    public function handleMethods(object $consumer, Message $message): array
    {
        $event = $message->payload();
        $methods = $this->findMethodsToHandleEvent($consumer);

        return $methods[$event::class] ?? [];
    }

    /**
     * @return array<string, string[]>
     */
    private function findMethodsToHandleEvent(object $handler): array
    {
        $handlerClass = new ReflectionClass($handler);

        $methods = $handlerClass->getMethods(ReflectionMethod::IS_PUBLIC);

        $handlers = [];

        foreach ($methods as $method) {
            if ( ! $type = $this->firstParameterType($method)) {
                continue;
            }

            $acceptedTypes = $this->acceptedTypes($type);

            foreach ($acceptedTypes as $type) {
                $handlers[$type->getName()][] = $method->getName();
            }
        }

        return $handlers;
    }

    protected function firstParameterType(ReflectionMethod $method): ?ReflectionType
    {
        $parameter = $method->getParameters()[0] ?? null;

        return $parameter?->getType();
    }

    /**
     * @return ReflectionNamedType[]
     */
    protected function acceptedTypes(ReflectionType $type): array
    {
        if ($type instanceof ReflectionNamedType) {
            return [$type];
        } elseif ($type instanceof ReflectionUnionType) {
            return $type->getTypes();
        }

        return [];
    }
}
