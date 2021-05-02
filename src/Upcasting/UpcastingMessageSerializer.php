<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Upcasting;

use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\Serialization\MessageSerializer;

class UpcastingMessageSerializer implements MessageSerializer
{
    private MessageSerializer $eventSerializer;
    private Upcaster $upcaster;

    public function __construct(MessageSerializer $eventSerializer, Upcaster $upcaster)
    {
        $this->eventSerializer = $eventSerializer;
        $this->upcaster = $upcaster;
    }

    public function serializeMessage(Message $message): array
    {
        return $this->eventSerializer->serializeMessage($message);
    }

    public function unserializePayload(array $payload): Message
    {
        $payload = $this->upcaster->upcast($payload);

        return $this->eventSerializer->unserializePayload($payload);
    }
}
