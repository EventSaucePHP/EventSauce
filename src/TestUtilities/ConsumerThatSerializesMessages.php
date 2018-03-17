<?php

namespace EventSauce\EventSourcing\TestUtilities;

use EventSauce\EventSourcing\Consumer;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\Serialization\ConstructingMessageSerializer;
use EventSauce\EventSourcing\Serialization\MessageSerializer;
use function iterator_to_array;
use PHPUnit\Framework\TestCase;
use function var_dump;

class ConsumerThatSerializesMessages implements Consumer
{
    /**
     * @var MessageSerializer
     */
    private $serializer;

    public function __construct(MessageSerializer $serializer = null)
    {
        $this->serializer = $serializer ?: new ConstructingMessageSerializer;
    }

    public function handle(Message $message)
    {
        $payload = $this->serializer->serializeMessage($message);
        $deserializedMessage = iterator_to_array($this->serializer->unserializePayload($payload))[0] ?? null;
        TestCase::assertEquals($message, $deserializedMessage);
    }
}