<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

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
