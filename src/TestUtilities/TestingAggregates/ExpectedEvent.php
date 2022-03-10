<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\TestUtilities\TestingAggregates;

class ExpectedEvent
{
    /**
     * @var callable|null
     */
    private $callable;

    public function __construct(protected string $class, ?callable $callable = null)
    {
        $this->callable = $callable;
    }

    public function assertEquals(object $recordedEvent): bool
    {
        $call = $this->callable;
        $callbackResult = $call !== null ? $call($recordedEvent) : null;
        return $recordedEvent instanceof $this->class && $callbackResult !== false;
    }
}
