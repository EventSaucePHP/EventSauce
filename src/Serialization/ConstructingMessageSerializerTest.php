<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Serialization;

use EventSauce\EventSourcing\DotSeparatedSnakeCaseInflector;
use EventSauce\EventSourcing\DummyAggregateRootId;
use EventSauce\EventSourcing\EventStub;
use EventSauce\EventSourcing\Header;
use EventSauce\EventSourcing\Message;
use PHPUnit\Framework\TestCase;

class ConstructingMessageSerializerTest extends TestCase
{
    /**
     * @test
     */
    public function serializing_messages_with_aggregate_root_ids(): void
    {
        $aggregateRootId = DummyAggregateRootId::generate();
        $inflector = new DotSeparatedSnakeCaseInflector();
        $aggregateRootIdType = $inflector->instanceToType($aggregateRootId);
        $message = new Message(new EventStub('original value'), [
            Header::AGGREGATE_ROOT_ID => $aggregateRootId,
            Header::AGGREGATE_ROOT_ID_TYPE => $aggregateRootIdType,
            Header::EVENT_TYPE => $inflector->classNameToType(EventStub::class),
        ]);
        $serializer = new ConstructingMessageSerializer();
        $serialized = $serializer->serializeMessage($message);
        $deserializedMessage = $serializer->unserializePayload($serialized);
        $messageWithConstructedAggregateRootId = $message->withHeader(Header::AGGREGATE_ROOT_ID, $aggregateRootId);
        $this->assertEquals($messageWithConstructedAggregateRootId, $deserializedMessage);
    }
}
