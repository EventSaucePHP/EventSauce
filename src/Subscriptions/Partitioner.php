<?php

namespace EventSauce\EventSourcing\Subscriptions;

use EventSauce\EventSourcing\Message;

interface Partitioner
{
    public function getPartitionKey(Message $message): ?string;
}
