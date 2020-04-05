<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Upcasting;

use EventSauce\EventSourcing\Serialization\SerializablePayload;

class UpcastedPayloadStub implements SerializablePayload
{
    /**
     * @var string
     */
    private $property;

    public function __construct(string $property)
    {
        $this->property = $property;
    }

    public function toPayload(): array
    {
        return ['property' => $this->property];
    }

    public static function fromPayload(array $payload): SerializablePayload
    {
        return new UpcastedPayloadStub($payload['property'] ?? 'undefined');
    }
}
