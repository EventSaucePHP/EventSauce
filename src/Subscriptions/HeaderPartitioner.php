<?php

namespace EventSauce\EventSourcing\Subscriptions;

use EventSauce\EventSourcing\Message;

class HeaderPartitioner implements Partitioner
{
    public function __construct(
        private string $headerName,
    ) {
    }

    public function getPartitionKey(Message $message): ?string
    {
        return $message->header($this->headerName);
    }
}
