<?php

namespace EventSauce\EventSourcing\Integration\Upcasting;

use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\Serialization\ConstructingMessageSerializer;
use EventSauce\EventSourcing\Serialization\EventType;
use EventSauce\EventSourcing\Serialization\UpcastingMessageSerializer;
use EventSauce\EventSourcing\Upcasting\DelegatingUpcaster;
use function iterator_to_array;
use PHPUnit\Framework\TestCase;
use EventSauce\EventSourcing\Time\TestClock;

class UpcastingEventsTest extends TestCase
{
    /**
     * @test
     */
    public function upcasting_works()
    {
        $clock = new TestClock();
        $pointInTime = $clock->pointInTime();

        $payload = [
            'type' => EventType::fromClassName(UpcastedEventStub::class)->toEventName(),
            'version' => 0,
            'aggregateRootId' => $uuid = AggregateRootId::create()->toString(),
            'timeOfRecording' => $pointInTime->toString(),
            'metadata' => [],
            'data' => [],
        ];

        $upcaster = new DelegatingUpcaster(new UpcasterStub());
        $serializer = new UpcastingMessageSerializer(new ConstructingMessageSerializer(), $upcaster);

        $message = iterator_to_array($serializer->unserializePayload($payload))[0];
        $expected = new Message(new UpcastedEventStub(
            new AggregateRootId($uuid),
            $pointInTime,
            'upcasted'
        ));

        $this->assertEquals($expected, $message);

        $serializeMessage = $serializer->serializeMessage($message);
        $expectedPayload = $payload = [
            'type' => EventType::fromClassName(UpcastedEventStub::class)->toEventName(),
            'version' => 1,
            'aggregateRootId' => $uuid,
            'timeOfRecording' => $pointInTime->toString(),
            'metadata' => [],
            'data' => [
                'property' => 'upcasted',
            ],
        ];

        $this->assertEquals($expectedPayload, $serializeMessage);
        $messageFromSerializedPayload = iterator_to_array($serializer->unserializePayload($expectedPayload))[0];

        $this->assertEquals($expected, $messageFromSerializedPayload);
    }
}