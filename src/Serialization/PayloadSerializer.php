<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Serialization;

interface PayloadSerializer
{
    public function serializeEvent(object $event): array;

    public function unserializePayload(string $className, array $payload): object;
}
