<?php

declare(strict_types=1);

namespace Without\Fields;

use EventSauce\EventSourcing\Serialization\SerializablePayload;

final class WithoutFields implements SerializablePayload
{
    public static function fromPayload(array $payload): SerializablePayload
    {
        return new WithoutFields();
    }

    public function toPayload(): array
    {
        return [];
    }

    /**
     * @codeCoverageIgnore
     */
    public static function with(): WithoutFields
    {
        return new WithoutFields();
    }
}
