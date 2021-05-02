<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

/**
 * This trait calls the apply[EventClassName] method
 * for every event it receives. Use this trait if
 * you want to ensure that you do not miss responding
 * to any type of event you raise in the aggregate.
 */
trait AggregateAlwaysAppliesEvents
{
    private int $aggregateRootVersion = 0;

    protected function apply(object $event): void
    {
        $parts = explode('\\', get_class($event));
        $this->{'apply' . end($parts)}($event);
        ++$this->aggregateRootVersion;
    }
}
