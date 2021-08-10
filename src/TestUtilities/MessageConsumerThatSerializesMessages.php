<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\TestUtilities;

use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageConsumer;
use EventSauce\EventSourcing\Serialization\ConstructingMessageSerializer;
use EventSauce\EventSourcing\Serialization\MessageSerializer;
use PHPUnit\Framework\TestCase;

class MessageConsumerThatSerializesMessages implements MessageConsumer
{
    /**
     * @var MessageSerializer
     */
    private $serializer;

    public function __construct(MessageSerializer $serializer = null)
    {
        $this->serializer = $serializer ?: new ConstructingMessageSerializer();
    }

    public function handle(Message $message): void
    {
        $payload = $this->serializer->serializeMessage($message);
        $deserializedMessage = $this->serializer->unserializePayload($payload);
        TestCase::assertEquals($message->payload(), $deserializedMessage->payload());
    }
}
