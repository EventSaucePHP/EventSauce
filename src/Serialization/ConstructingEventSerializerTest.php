<?php

declare (strict_types=1);

namespace EventSauce\EventSourcing\Serialization;

use PHPStan\Testing\TestCase;
use EventSauce\EventSourcing\EventStub;

final class ConstructingEventSerializerTest extends TestCase
{
    /**
     * @test
     */
    public function serializes_serializable_event()
    {
        $event = EventStub::create('some value');
        $serializer = new ConstructingEventSerializer();
        $data = $serializer->serializeEvent($event);

        $this->assertSame(['value' => 'some value'], $data);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function cannot_serialize_non_serializable_event()
    {
        $serializer = new ConstructingEventSerializer();
        $serializer->serializeEvent(new class(){});
    }

    /**
     * @test
     */
    public function unserialize_into_serializable_event()
    {
        $serializer = new ConstructingEventSerializer();
        $object = $serializer->unserializePayload(EventStub::class, ['value' => 'some value']);

        $this->assertInstanceOf(EventStub::class, $object);
        $this->assertAttributeSame('some value', 'value', $object);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function cannot_unserialize_into_non_serializable_event()
    {
        $serializer = new ConstructingEventSerializer();
        $serializer->unserializePayload(get_class(new class(){}), ['value' => 'some value']);
    }
}
