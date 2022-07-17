<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use function strrpos;
use function substr;

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
        $className = get_class($event);
        $this->{'apply' . substr($className, (strrpos($className, '\\') ?: -1) + 1)}($event);
        ++$this->aggregateRootVersion;
    }
}
