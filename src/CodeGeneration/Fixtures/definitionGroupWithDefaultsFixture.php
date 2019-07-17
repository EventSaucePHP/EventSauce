<?php

namespace Group\With\Defaults;

use EventSauce\EventSourcing\Serialization\SerializablePayload;

final class EventWithDescription implements SerializablePayload
{
    /**
     * @var string
     */
    private $description;

    public function __construct(
        string $description
    ) {
        $this->description = $description;
    }

    public function description(): string
    {
        return $this->description;
    }
    public static function fromPayload(array $payload): SerializablePayload
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
    public static function with(): EventWithDescription
    {
        return new EventWithDescription(
            (string) 'This is a description.'
        );
    }
}
