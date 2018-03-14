<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\AggregateRootBehaviour;

use EventSauce\EventSourcing\Event;

trait EventApplyingBehaviour
{
    protected function apply(Event $event)
    {
        $parts = explode('\\', get_class($event));
        $this->{'apply' . end($parts)}($event);
    }
}
