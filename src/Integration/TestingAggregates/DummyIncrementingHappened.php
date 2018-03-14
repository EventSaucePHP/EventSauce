<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Integration\TestingAggregates;

use EventSauce\EventSourcing\Event;

/**
 * @codeCoverageIgnore
 */
class DummyIncrementingHappened implements Event
{
    /**
     * @var int
     */
    private $number;

    public function __construct(int $number)
    {
        $this->number = $number;
    }

    public function toPayload(): array
    {
        return [];
    }

    public static function fromPayload(array $payload): Event
    {
        return new DummyIncrementingHappened($payload['number']);
    }

    public function number(): int
    {
        return $this->number;
    }
}
