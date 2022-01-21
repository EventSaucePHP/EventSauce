<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use EventSauce\EventSourcing\Serialization\SerializablePayload;

class DummyEvent implements SerializablePayload
{
    public function toPayload(): array
    {
        return [];
    }

    public static function fromPayload(array $payload): SerializablePayload
    {
        return new DummyEvent();
    }
}
