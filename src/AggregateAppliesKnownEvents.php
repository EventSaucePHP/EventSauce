<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use function method_exists;

/**
 * This trait calls the apply[EventClassName] method
 * only for the events it has an apply function for.
 * Use this trait when you have many events that you
 * do not have an apply method for. This is common
 * for when you record many events for analytics.
 */
trait AggregateAppliesKnownEvents
{
    private int $aggregateRootVersion = 0;

    public function apply(object $event): void
    {
        $parts = explode('\\', get_class($event));
        $methodName = 'apply' . end($parts);

        if (method_exists($this, $methodName)) {
            $this->{$methodName}($event);
        }

        ++$this->aggregateRootVersion;
    }
}
