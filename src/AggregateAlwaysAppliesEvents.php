<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

trait AggregateAlwaysAppliesEvents
{
    private $aggregateRootVersion = 0;

    protected function apply(object $event): void
    {
        $parts = explode('\\', get_class($event));
        $this->{'apply' . end($parts)}($event);
        ++$this->aggregateRootVersion;
    }
}
