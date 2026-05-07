<?php

namespace EventSauce\EventSourcing\Subscriptions;

use EventSauce\EventSourcing\Message;
use Generator;

interface SubscriptionProvider
{
    /**
     * @return Generator<mixed, Message, void, Checkpoint>
     */
    public function getEventsSinceCheckpoint(Checkpoint $checkpoint): Generator;
}
