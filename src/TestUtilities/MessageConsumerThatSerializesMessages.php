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
        $payloadAsString = json_encode($payload);
        if ($payloadAsString === false) {
            TestCase::fail('Payload could not be serialized');
        }

        $deserializedMessage = $this->serializer->unserializePayload(
            json_decode($payloadAsString, true)
        );
        TestCase::assertEquals($message->event(), $deserializedMessage->event());
    }
}
