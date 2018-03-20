<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Serialization;

interface SerializableEvent
{
    public function toPayload(): array;

    public static function fromPayload(array $payload): SerializableEvent;
}
