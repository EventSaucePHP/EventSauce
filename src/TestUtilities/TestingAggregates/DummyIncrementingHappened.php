<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\TestUtilities\TestingAggregates;

use EventSauce\EventSourcing\Serialization\SerializablePayload;

/**
 * @codeCoverageIgnore
 */
class DummyIncrementingHappened implements SerializablePayload
{
    /**
     * @var int
     */
    private $number = 0;

    public function __construct(int $number)
    {
        $this->number = $number;
    }

    public function toPayload(): array
    {
        return ['number' => $this->number];
    }

    public static function fromPayload(array $payload): SerializablePayload
    {
        return new DummyIncrementingHappened($payload['number']);
    }

    public function number(): int
    {
        return $this->number;
    }
}
