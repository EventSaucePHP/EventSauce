<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Serialization;

interface PayloadSerializer
{
    public function serializePayload(object $event): array;

    /**
     * @template T of object
     * @param class-string<T> $className
     * @return T
     */
    public function unserializePayload(string $className, array $payload): object;
}
