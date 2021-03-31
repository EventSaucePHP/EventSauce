<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

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
        static $hasMethodCache = [];
        $parts = explode('\\', get_class($event));
        $methodName = 'apply' . end($parts);
        $shouldApply = $hasMethodCache[$methodName] ?? method_exists($this, $methodName);

        if ($shouldApply) {
            $this->{$methodName}($event);
        }

        ++$this->aggregateRootVersion;
    }
}
