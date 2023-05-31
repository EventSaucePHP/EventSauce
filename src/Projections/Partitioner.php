<?php

namespace EventSauce\EventSourcing\Projections;

use EventSauce\EventSourcing\Message;

interface Partitioner
{
    public function getPartitionKey(Message $message): string;
}
