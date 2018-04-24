<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Serialization;

use PHPStan\Testing\TestCase;
use EventSauce\EventSourcing\EventStub;

final class TypeValidatingEventSerializerTest extends TestCase
{
    private $serializer;
    private $innerSerializer;

    public function setUp()
    {
        $this->serializer = new TypeValidatingEventSerializer(
            new ConstructingEventSerializer(),
            SerializableEvent::class
        );
    }

    /**
     * @test
     */
    public function is_an_event_serializer()
    {
        $this->assertInstanceOf(EventSerializer::class, $this->serializer);
    }

    /**
     * @test
     */
    public function delegates_serialization_to_decorated_serializer()
    {
        $event = EventStub::create('some value');
        $data = $this->serializer->serializeEvent($event);
        $this->assertSame(['value' => 'some value'], $data);
    }

    /**
     * @test
     */
    public function delegates_unserialization_to_decorated_serializer()
    {
        $object = $this->serializer->unserializePayload(EventStub::class, ['value' => 'some value']);
        $this->assertSame(['value' => 'some value'], $object->toPayload());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Cannot serialize event that does not implement "EventSauce\EventSourcing\Serialization\SerializableEvent".
     */
    public function cannot_serialize_non_instance_of_provided_event_classname()
    {
        $this->serializer->serializeEvent(new class() {});
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Cannot unserialize payload into an event that does not implement "EventSauce\EventSourcing\Serialization\SerializableEvent".
     */
    public function cannot_unserialize_into_non_serializable_event()
    {
        $this->serializer->unserializePayload(get_class(new class() {}), ['value' => 'some value']);
    }
}
