<?php

namespace EventSauce\EventSourcing\Serialization;

use EventSauce\EventSourcing\Message;
use Generator;

interface MessageSerializer
{
    public function serializeMessage(Message $message): array;

    public function unserializePayload(array $payload): Generator;
}