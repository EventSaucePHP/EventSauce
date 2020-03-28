<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\TestUtilities;

use EventSauce\EventSourcing\MessageConsumer;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\Serialization\ConstructingMessageSerializer;
use EventSauce\EventSourcing\Serialization\MessageSerializer;
use function iterator_to_array;
use PHPUnit\Framework\TestCase;
use stdClass;

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
        $deserializedMessage = iterator_to_array($this->serializer->unserializePayload($payload))[0]
            ?? new Message(new stdClass());
        TestCase::assertEquals($message->event(), $deserializedMessage->event());
    }
}
