<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Upcasting;

use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\Serialization\MessageSerializer;
use Generator;

class UpcastingMessageSerializer implements MessageSerializer
{
    /**
     * @var MessageSerializer
     */
    private $eventSerializer;

    /**
     * @var Upcaster
     */
    private $upcaster;

    public function __construct(MessageSerializer $eventSerializer, Upcaster $upcaster)
    {
        $this->eventSerializer = $eventSerializer;
        $this->upcaster = $upcaster;
    }

    public function serializeMessage(Message $message): array
    {
        return $this->eventSerializer->serializeMessage($message);
    }

    public function unserializePayload(array $payload): Generator
    {
        foreach ($this->upcaster->upcast($payload) as $payload) {
            yield from $this->eventSerializer->unserializePayload($payload);
        }
    }
}
