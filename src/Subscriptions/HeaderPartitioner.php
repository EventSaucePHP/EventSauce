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
        $key = $message->header($this->headerName);
        if(!is_string($key)) {
            return null;
        }
        return $key;
    }
}
