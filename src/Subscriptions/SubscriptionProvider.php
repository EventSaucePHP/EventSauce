<?php

namespace EventSauce\EventSourcing\Subscriptions;

use EventSauce\EventSourcing\Message;
use Generator;

interface SubscriptionProvider
{
    /**
     * @return Generator<int, Message, void, Checkpoint>
     */
    public function getEventsSinceCheckpoint(Checkpoint $checkpoint, int $maxEvents = 100): Generator;
}
