<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Serialization;

use EventSauce\EventSourcing\Message;
use Generator;

interface MessageSerializer
{
    /**
     * @param Message $message
     *
     * @return array
     */
    public function serializeMessage(Message $message): array;

    /**
     * @param array $payload
     *
     * @return Generator
     */
    public function unserializePayload(array $payload): Generator;
}
