<?php

declare(strict_types=1);

namespace Group\With\Defaults;

use EventSauce\EventSourcing\Serialization\SerializablePayload;

final class EventWithDescription implements SerializablePayload
{
    public function __construct(
        private string $description
    ) {
    }

    public function description(): string
    {
        return $this->description;
    }

    public static function fromPayload(array $payload): self
    {
        return new EventWithDescription(
            (string) $payload['description']
        );
    }

    public function toPayload(): array
    {
        return [
            'description' => (string) $this->description,
        ];
    }

    /**
     * @codeCoverageIgnore
     */
    public function withDescription(string $description): EventWithDescription
    {
        $clone = clone $this;
        $clone->description = $description;

        return $clone;
    }

    /**
     * @codeCoverageIgnore
     */
    public static function withDefaults(): EventWithDescription
    {
        return new EventWithDescription(
            (string) 'This is a description.'
        );
    }
}
