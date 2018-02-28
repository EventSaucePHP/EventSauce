<?php

namespace EventSauce\EventSourcing\Integration\Upcasting;

use EventSauce\EventSourcing\DotSeparatedSnakeCaseInflector;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\Serialization\ConstructingMessageSerializer;
use EventSauce\EventSourcing\Serialization\UpcastingMessageSerializer;
use EventSauce\EventSourcing\Time\TestClock;
use EventSauce\EventSourcing\Upcasting\DelegatingUpcaster;
use EventSauce\EventSourcing\UuidAggregateRootId;
use PHPUnit\Framework\TestCase;
use function iterator_to_array;

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
            'type'            => (new DotSeparatedSnakeCaseInflector())->classNameToEventName(UpcastedEventStub::class),
            'version'         => 0,
            'timeOfRecording' => $pointInTime->toString(),
            'metadata'        => [],
            'data'            => [],
        ];

        $upcaster = new DelegatingUpcaster(new UpcasterStub());
        $serializer = new UpcastingMessageSerializer(new ConstructingMessageSerializer(UuidAggregateRootId::class), $upcaster);

        $message = iterator_to_array($serializer->unserializePayload($payload))[0];
        $expected = new Message(new UpcastedEventStub(
            $pointInTime,
            'upcasted'
        ));

        $this->assertEquals($expected, $message);

        $serializeMessage = $serializer->serializeMessage($message);
        $expectedPayload = $payload = [
            'type'            => (new DotSeparatedSnakeCaseInflector())->classNameToEventName(UpcastedEventStub::class),
            'version'         => 1,
            'timeOfRecording' => $pointInTime->toString(),
            'metadata'        => [],
            'data'            => [
                'property'        => 'upcasted',
                '__event_version' => 1,
            ],
        ];

        $this->assertEquals($expectedPayload, $serializeMessage);
        $messageFromSerializedPayload = iterator_to_array($serializer->unserializePayload($expectedPayload))[0];

        $this->assertEquals($expected, $messageFromSerializedPayload);
    }
}