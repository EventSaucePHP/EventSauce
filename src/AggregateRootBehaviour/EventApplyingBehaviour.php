<?php

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