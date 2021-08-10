<?php

declare(strict_types=1);

namespace Without\Fields;

use EventSauce\EventSourcing\Serialization\SerializablePayload;

final class WithoutFields implements SerializablePayload
{
    public static function fromPayload(array $payload): static
    {
        return new static();
    }

    public function toPayload(): array
    {
        return [];
    }

    /**
     * @codeCoverageIgnore
     */
    public static function withDefaults(): WithoutFields
    {
        return new WithoutFields();
    }
}
