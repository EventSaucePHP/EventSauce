<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Integration\Upcasting;

use EventSauce\EventSourcing\Event;

class UpcastedEventStub implements Event
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

    public static function fromPayload(array $payload): Event
    {
        return new UpcastedEventStub($payload['property'] ?? 'undefined');
    }
}
