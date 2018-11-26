<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Serialization;

use PHPStan\Testing\TestCase;
use EventSauce\EventSourcing\EventStub;
use stdClass;

final class TypeValidatingEventSerializerTest extends TestCase
{
    /**
     * @var TypeValidatingEventSerializer
     */
    private $serializer;

    /**
     * @var ConstructingEventSerializer
     */
    private $innerSerializer;

    public function setUp()
    {
        $this->innerSerializer = new ConstructingEventSerializer();
        $this->serializer = new TypeValidatingEventSerializer(
            $this->innerSerializer,
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
        $this->assertSame(
            $this->innerSerializer->serializeEvent($event),
            $this->serializer->serializeEvent($event)
        );
    }

    /**
     * @test
     */
    public function delegates_unserialization_to_decorated_serializer()
    {
        $payloadArgs = [EventStub::class, ['value' => 'some value']];

        $this->assertSame(
            $this->innerSerializer->unserializePayload(...$payloadArgs)->toPayload(),
            $this->serializer->unserializePayload(...$payloadArgs)->toPayload()
        );
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Cannot serialize event that does not implement "EventSauce\EventSourcing\Serialization\SerializableEvent".
     */
    public function cannot_serialize_non_instance_of_provided_event_classname()
    {
        $this->serializer->serializeEvent(new stdClass());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Cannot unserialize payload into an event that does not implement "EventSauce\EventSourcing\Serialization\SerializableEvent".
     */
    public function cannot_unserialize_into_non_serializable_event()
    {
        $this->serializer->unserializePayload(stdClass::class, ['value' => 'some value']);
    }
}
