<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\AggregateRootBehaviour;

trait EventApplyingBehaviour
{
    protected function apply(object $event)
    {
        $parts = explode('\\', get_class($event));
        $this->{'apply' . end($parts)}($event);
    }
}
