<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Serialization;

use PHPStan\Testing\TestCase;
use EventSauce\EventSourcing\EventStub;

final class ConstructingEventSerializerTest extends TestCase
{
    private $serializer;

    public function setUp()
    {
        $this->serializer = new ConstructingEventSerializer();
    }

    /**
     * @test
     */
    public function serializes_serializable_event()
    {
        $event = EventStub::create('some value');
        $data = $this->serializer->serializeEvent($event);

        $this->assertSame(['value' => 'some value'], $data);
    }

    /**
     * @test
     */
    public function unserialize_into_serializable_event()
    {
        $object = $this->serializer->unserializePayload(EventStub::class, ['value' => 'some value']);

        $this->assertInstanceOf(EventStub::class, $object);
        $this->assertAttributeSame('some value', 'value', $object);
    }
}
